<?php include 'components/PHP/header.php'; 

$lang_id = get_language_id($pdo, get_lang_code());

// Fetch dynamic site content
$stmt_content = $pdo->prepare("SELECT content_key, content_value FROM site_content WHERE language_id = ?");
$stmt_content->execute([$lang_id]);
$site_data = $stmt_content->fetchAll(PDO::FETCH_KEY_PAIR);

// Helper to get site content with fallback
function get_site_content($key, $fallback_fn_name, $site_data) {
    return isset($site_data[$key]) ? $site_data[$key] : $fallback_fn_name($key);
}

// Fetch latest news (The very newest one will be featured)
$stmt_latest = $pdo->prepare("
    SELECT n.id, n.publish_date, nt.title, nt.content, n.image_url, n.link_url
    FROM news n
    JOIN news_translations nt ON n.id = nt.news_id
    WHERE nt.language_id = ?
    ORDER BY n.publish_date DESC LIMIT 4
");
$stmt_latest->execute([$lang_id]);
$all_recent_news = $stmt_latest->fetchAll();

$featured_news = array_shift($all_recent_news); // Newest
$latest_news = $all_recent_news; // Next 3

// Fetch gallery items (Limit 8)
$stmt_gallery = $pdo->prepare("
    SELECT g.*, gt.title 
    FROM gallery g 
    JOIN gallery_translations gt ON g.id = gt.gallery_id 
    WHERE gt.language_id = ?
    ORDER BY g.created_at DESC LIMIT 8
");
$stmt_gallery->execute([$lang_id]);
$gallery_items = $stmt_gallery->fetchAll();

// Fetch all partners for the side scroller
$stmt_all_partners = $pdo->prepare("SELECT * FROM partners ORDER BY name ASC");
$stmt_all_partners->execute();
$all_partners = $stmt_all_partners->fetchAll();
?>

<!-- HERO SECTION -->
<section class="hero-wide">
    <img src="components/images/others/insea_home.jpg" alt="INSEA Campus Wide">
</section>

<!-- WELCOME FLEX -->
<section class="welcome-flex">
    <div class="welcome-img">
        <img src="components/images/others/insea_home_image2.png" alt="INSEA Welcome">
    </div>
    <div class="welcome-text">
        <h2><?php echo htmlspecialchars(get_site_content('welcome_title', '__', $site_data)); ?></h2>
        <p>
            <?php echo nl2br(htmlspecialchars(get_site_content('welcome_text', '__', $site_data))); ?>
        </p>
        <a href="#" class="link-arrow"><?php echo __('explorer_plus'); ?></a>
    </div>
</section>

<!-- ACTUALITES SECTION -->
<section>
    <div class="section-header">
        <h2><?php echo __('actualites'); ?></h2>
        <div class="line"></div>
    </div>

    <!-- LE PLUS RÉCENT (Featured First) -->
    <?php if ($featured_news): ?>
    <section class="featured-news">
        <div class="featured-img">
            <?php if ($featured_news['image_url']): ?>
                <img src="<?php 
                    echo (strpos($featured_news['image_url'], 'http') === 0) 
                        ? htmlspecialchars($featured_news['image_url']) 
                        : $assets_path . 'images/' . htmlspecialchars($featured_news['image_url']); 
                ?>" alt="News Image" class="cover-img">            <?php else: ?>
                <?php echo __('logo_title'); ?>
            <?php endif; ?>
        </div>
        <div class="featured-content">
            <span class="tag-recent"><?php echo __('featured_recent'); ?></span>
            <h2><?php echo htmlspecialchars($featured_news['title']); ?></h2>
            <p><?php echo truncate_text($featured_news['content'], 250); ?></p>
            <a href="components/PHP/article.php?id=<?php echo $featured_news['id']; ?>" class="link-arrow link-white"><?php echo __('read_more'); ?></a>
        </div>
    </section>
    <?php endif; ?>

    <!-- NEWS GRID -->
    <div class="news-grid">
        <?php foreach ($latest_news as $news): ?>
        <article class="news-card">
            <div class="news-img">
                <?php if ($news['image_url']): ?>
                    <img src="<?php 
                        echo (strpos($news['image_url'], 'http') === 0) 
                            ? htmlspecialchars($news['image_url']) 
                            : $assets_path . 'images/' . htmlspecialchars($news['image_url']); 
                    ?>" alt="News Image" class="cover-img">
                <?php else: ?>
                    NEWS
                <?php endif; ?>
            </div>
            <div class="news-content">
                <span class="news-date"><?php echo date('d M Y', strtotime($news['publish_date'])); ?></span>
                <h3><?php echo htmlspecialchars($news['title']); ?></h3>
                <p><?php echo truncate_text($news['content'], 120); ?></p>
                <a href="components/PHP/article.php?id=<?php echo $news['id']; ?>" class="link-arrow"><?php echo __('read_more'); ?></a>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <!-- VIEW ALL BUTTON -->
    <div class="btn-container">
        <a href="components/PHP/actualites.php" class="btn-outline"><?php echo __('voir_tous'); ?></a>
    </div>
</section>

<!-- HISTOIRE MISSION ORGANISATION -->
<section class="hmo-section">
    <div class="hmo-container">
        <div class="hmo-item">
            <h3><?php echo __('history'); ?></h3>
            <p><?php echo __('hmo_history_text'); ?></p>
            <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
        </div>
        
        <div class="hmo-item">
            <h3><?php echo __('mission'); ?></h3>
            <p><?php echo __('hmo_mission_text'); ?></p>
            <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
        </div>

        <div class="hmo-item">
            <h3><?php echo __('organisation'); ?></h3>
            <p><?php echo __('hmo_org_text'); ?></p>
            <a href="#" class="link-arrow"><?php echo __('read_more'); ?></a>
        </div>
    </div>
</section>

<!-- MOT DU DIRECTEUR -->
<section class="mot-section">
    <div class="mot-container">
        <div class="mot-image-wrap">
            <div class="mot-image">PHOTO</div>
        </div>
        <div class="mot-text">
            <h2><?php echo __('director_word'); ?></h2>
            <blockquote><?php echo htmlspecialchars(get_site_content('director_quote', '__', $site_data)); ?></blockquote>
            <div class="mot-author"><?php echo __('director_name'); ?><span><?php echo __('director_sub'); ?></span></div>
        </div>
    </div>
</section>

<!-- ESPACE ETUDIANTS & CHIFFRES -->
<section class="espace-etudiants-section">
    <div class="section-header mt-0">
        <h2 class="text-dark"><?php 
            $student_space = __('student_space');
            $parts = explode(' ', $student_space, 2);
            echo $parts[0]; 
            if(isset($parts[1])) echo ' <span class="text-green">' . $parts[1] . '</span>';
        ?></h2>
    </div>
    
    <div class="espace-cards-grid">
        <article class="espace-card">
            <div class="espace-icon-wrap">
                <div class="espace-icon-inner">
                    <img src="components/images/logos/admission.png" alt="Accès et admissions">
                </div>
            </div>
            <h3><?php echo __('student_link_admission'); ?></h3>
            <a href="components/PHP/admission.php" class="espace-link"><?php echo __('more_infos'); ?></a>
        </article>
        
        <article class="espace-card">
            <div class="espace-icon-wrap">
                <div class="espace-icon-inner">
                    <img src="components/images/logos/calendrier.png" alt="Emploi du temps">
                </div>
            </div>
            <h3><?php echo __('student_link_schedule'); ?></h3>
            <a href="components/PHP/calendrier_scolaire.php" class="espace-link"><?php echo __('more_infos'); ?></a>
        </article>

        <article class="espace-card">
            <div class="espace-icon-wrap">
                <div class="espace-icon-inner">
                    <img src="components/images/logos/preinscription.png" alt="Préinscription">
                </div>
            </div>
            <h3><?php echo __('student_link_preins'); ?></h3>
            <a href="components/PHP/admission_1.php" class="espace-link"><?php echo __('more_infos'); ?></a>
        </article>

        <article class="espace-card">
            <div class="espace-icon-wrap">
                <div class="espace-icon-inner">
                    <img src="components/images/logos/planning.png" alt="Planing des examens">
                </div>
            </div>
            <h3><?php echo __('student_link_exams'); ?></h3>
            <a href="components/PHP/calendrier_scolaire.php" class="espace-link"><?php echo __('more_infos'); ?></a>
        </article>
    </div>

    <div class="chiffres-icons-grid">
        <div class="chiffre-icon-item">
            <img src="components/images/logos/inscrits.png" alt="Etudiants inscrits">
            <div class="chiffre-number">1411</div>
            <div class="chiffre-label">Etudiants Inscrits</div>
        </div>
        <div class="chiffre-icon-item">
            <img src="components/images/logos/filiere.png" alt="Filières">
            <div class="chiffre-number">11</div>
            <div class="chiffre-label">Filières</div>
        </div>
        <div class="chiffre-icon-item">
            <img src="components/images/logos/recherche.png" alt="Centres de recherche">
            <div class="chiffre-number">1</div>
            <div class="chiffre-label">Centres de Recherche</div>
        </div>
        <div class="chiffre-icon-item">
            <img src="components/images/logos/equipe_recherche.png" alt="Equipes de recherche">
            <div class="chiffre-number">7</div>
            <div class="chiffre-label">Equipes de Recherche</div>
        </div>
    </div>
</section>

<!-- ADMINISTRATION SECTION -->
<section class="admin-section">
    <div class="section-header mt-0">
        <h2><?php echo __('admin_title'); ?></h2>
        <div class="line"></div>
    </div>
    <div class="admin-grid">
        <article class="admin-card">
            <div class="admin-photo"></div>
            <h4><?php echo __('director_name'); ?></h4>
            <p><?php echo __('admin_role_director'); ?></p>
        </article>
        <article class="admin-card">
            <div class="admin-photo"></div>
            <h4>NOM ET PRÉNOM</h4>
            <p><?php echo __('admin_role_deputy'); ?></p>
        </article>
        <article class="admin-card">
            <div class="admin-photo"></div>
            <h4>NOM ET PRÉNOM</h4>
            <p><?php echo __('admin_role_deputy'); ?></p>
        </article>
        <article class="admin-card">
            <div class="admin-photo"></div>
            <h4>NOM ET PRÉNOM</h4>
            <p><?php echo __('admin_role_secretary'); ?></p>
        </article>
    </div>
</section>

<!-- ORGANIGRAMME -->
<section class="orga-section">
    <div class="section-header mt-0">
        <h2><?php echo __('organigramme'); ?></h2>
        <div class="line"></div>
    </div>
    <div class="orga-container">
        <div class="orga-box">
            [ IMAGE ORGANIGRAMME ]
        </div>
    </div>
</section>

<!-- VIDEO SECTION -->
<section class="video-section">
    <div class="video-container">
        <iframe src="https://www.youtube.com/embed/SHy1NKN56yg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
    <div class="video-description">
        <h3><?php echo __('discover_video'); ?></h3>
        <p><?php echo __('video_desc'); ?></p>
        <a href="#" class="link-arrow"><?php echo __('video_view_more'); ?></a>
    </div>
</section>

<!-- GALERIE SECTION -->
<section class="gallery-section">
    <div class="section-header mt-0">
        <h2><?php echo __('gallery'); ?></h2>
        <div class="line"></div>
    </div>
    
    <div class="gallery-filters">
        <span class="filter-btn active" data-category="all"><?php echo __('gallery_filter_all'); ?></span>
        <span class="filter-btn" data-category="etudiants"><?php echo __('gallery_filter_students'); ?></span>
        <span class="filter-btn" data-category="evenements"><?php echo __('gallery_filter_events'); ?></span>
        <span class="filter-btn" data-category="partenariats"><?php echo __('gallery_filter_partners'); ?></span>
        <span class="filter-btn" data-category="recherche"><?php echo __('gallery_filter_research'); ?></span>
    </div>

    <div class="gallery-grid" id="main-gallery">
        <?php foreach ($gallery_items as $item): ?>
            <div class="gallery-item" 
                 data-category="<?php echo $item['category']; ?>" 
                 data-title="<?php echo $item['title'] ? htmlspecialchars($item['title']) : ''; ?>"
                 data-link="<?php echo $item['link_url'] ? htmlspecialchars($item['link_url']) : ''; ?>"
                 onclick="openLightbox(this)">
                <img src="<?php 
                    echo (strpos($item['image_url'], 'http') === 0) 
                        ? htmlspecialchars($item['image_url']) 
                        : $assets_path . 'images/' . htmlspecialchars($item['image_url']); 
                ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                <div class="gallery-overlay">
                    <span><?php echo htmlspecialchars($item['title']); ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- LIGHTBOX MODAL -->
<div id="lightbox" class="lightbox">
    <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
    <div class="lightbox-content">
        <img id="lightbox-img" src="" alt="Full View">
        <div class="lightbox-caption">
            <h3 id="lightbox-title"></h3>
            <a id="lightbox-link" href="#" target="_blank" class="btn-gold" style="display: none; margin-top: 10px; padding: 8px 20px; text-decoration: none; font-size: 0.9rem; border-radius: 4px;">Voir l'article</a>
        </div>
        <a class="prev" onclick="changeImage(-1)">&#10094;</a>
        <a class="next" onclick="changeImage(1)">&#10095;</a>
    </div>
</div>

<script>
let currentGalleryData = [];
let currentIndex = 0;

function openLightbox(element) {
    const visibleItems = Array.from(document.querySelectorAll('#main-gallery .gallery-item')).filter(item => item.style.display !== 'none');
    
    currentGalleryData = visibleItems.map(item => ({
        src: item.querySelector('img').src,
        title: item.getAttribute('data-title'),
        link: item.getAttribute('data-link')
    }));
    
    currentIndex = currentGalleryData.findIndex(data => data.src === element.querySelector('img').src);
    
    updateLightboxContent();
    
    const lb = document.getElementById('lightbox');
    lb.style.display = 'flex';
    setTimeout(() => lb.classList.add('active'), 10);
}

function updateLightboxContent() {
    const data = currentGalleryData[currentIndex];
    const img = document.getElementById('lightbox-img');
    const title = document.getElementById('lightbox-title');
    const link = document.getElementById('lightbox-link');
    
    img.src = data.src;
    title.textContent = (data.title && data.title !== "null") ? data.title : "";
    
    // Strict check for link: not null, not empty string, and not literal "null" string
    if (data.link && data.link !== "" && data.link !== "null") {
        link.href = data.link;
        link.style.display = 'inline-block';
    } else {
        link.style.display = 'none';
    }
}

function closeLightbox() {
    const lb = document.getElementById('lightbox');
    lb.classList.remove('active');
    setTimeout(() => lb.style.display = 'none', 400);
}

function changeImage(n) {
    currentIndex += n;
    if (currentIndex >= currentGalleryData.length) currentIndex = 0;
    if (currentIndex < 0) currentIndex = currentGalleryData.length - 1;
    
    const img = document.getElementById('lightbox-img');
    img.style.opacity = '0';
    setTimeout(() => {
        updateLightboxContent();
        img.style.opacity = '1';
    }, 200);
}

// Keyboard Navigation
document.addEventListener('keydown', function(e) {
    const lb = document.getElementById('lightbox');
    if (lb.style.display === 'flex') {
        if (e.key === 'ArrowLeft') changeImage(-1);
        if (e.key === 'ArrowRight') changeImage(1);
        if (e.key === 'Escape') closeLightbox();
    }
});

// Filtering Logic with Animation
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelector('.filter-btn.active').classList.remove('active');
        this.classList.add('active');
        
        const category = this.getAttribute('data-category');
        const items = document.querySelectorAll('.gallery-item');
        
        items.forEach(item => {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.8)';
            setTimeout(() => {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = 'block';
                    setTimeout(() => {
                        item.style.opacity = '1';
                        item.style.transform = 'scale(1)';
                    }, 50);
                } else {
                    item.style.display = 'none';
                }
            }, 300);
        });
    });
});
</script>

<!-- PARTENARIATS SIDE SCROLLER -->
<section class="partners-section">
    <div class="section-header mt-0">
        <h2><?php echo __('partners'); ?></h2>
        <div class="line"></div>
    </div>
    
    <div class="partners-viewport">
        <div class="partners-track">
            <?php foreach ($all_partners as $partner): ?>
                <div class="partner-logo" data-name="<?php echo htmlspecialchars($partner['name']); ?>">
                    <img src="<?php 
                        echo (strpos($partner['logo_url'], 'http') === 0) 
                            ? htmlspecialchars($partner['logo_url']) 
                            : $assets_path . 'images/' . htmlspecialchars($partner['logo_url']); 
                    ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>">
                </div>
            <?php endforeach; ?>
            <!-- Duplicates for seamless loop -->
            <?php foreach ($all_partners as $partner): ?>
                <div class="partner-logo" data-name="<?php echo htmlspecialchars($partner['name']); ?>">
                    <img src="<?php 
                        echo (strpos($partner['logo_url'], 'http') === 0) 
                            ? htmlspecialchars($partner['logo_url']) 
                            : $assets_path . 'images/' . htmlspecialchars($partner['logo_url']); 
                    ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'components/PHP/footer.php'; ?>
