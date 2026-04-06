<?php 
include 'header.php'; 
require_once 'db_connect.php';

// Fetch national partners
$stmt_nat = $pdo->prepare("SELECT * FROM partners WHERE type = 'national' ORDER BY name ASC");
$stmt_nat->execute();
$partners_nat = $stmt_nat->fetchAll();

// Fetch international partners
$stmt_inter = $pdo->prepare("SELECT * FROM partners WHERE type = 'international' ORDER BY name ASC");
$stmt_inter->execute();
$partners_inter = $stmt_inter->fetchAll();
?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('partners_title'); ?></h1>
    </section>

    <section style="max-width: 1100px; margin: 60px auto; padding: 0 20px; text-align: center;">
        <p style="font-size: 1.2rem; line-height: 1.8; color: var(--gray-600); max-width: 800px; margin: 0 auto 60px;">
            <?php echo __('partners_desc'); ?>
        </p>

        <!-- NATIONAL PARTNERS -->
        <?php if (!empty($partners_nat)): ?>
        <div style="margin-bottom: 80px;">
            <h2 style="color: var(--insea-green); margin-bottom: 40px; font-weight: 800; border-bottom: 3px solid var(--insea-gold); display: inline-block; padding-bottom: 8px;">
                <?php echo __('partners_nat_title'); ?>
            </h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 50px; align-items: center;">
                <?php foreach ($partners_nat as $partner): ?>
                    <div style="padding: 20px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <img src="<?php 
                            echo (strpos($partner['logo_url'], 'http') === 0) 
                                ? htmlspecialchars($partner['logo_url']) 
                                : $assets_path . 'images/' . htmlspecialchars($partner['logo_url']); 
                        ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>" style="max-width: 100%; height: auto; max-height: 120px; object-fit: contain;">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- INTERNATIONAL PARTNERS -->
        <?php if (!empty($partners_inter)): ?>
        <div>
            <h2 style="color: var(--insea-green); margin-bottom: 40px; font-weight: 800; border-bottom: 3px solid var(--insea-gold); display: inline-block; padding-bottom: 8px;">
                <?php echo __('partners_inter_title'); ?>
            </h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 50px; align-items: center;">
                <?php foreach ($partners_inter as $partner): ?>
                    <div style="padding: 20px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                        <img src="<?php 
                            echo (strpos($partner['logo_url'], 'http') === 0) 
                                ? htmlspecialchars($partner['logo_url']) 
                                : $assets_path . 'images/' . htmlspecialchars($partner['logo_url']); 
                        ?>" alt="<?php echo htmlspecialchars($partner['name']); ?>" style="max-width: 100%; height: auto; max-height: 120px; object-fit: contain;">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </section>
</main>

<?php include 'footer.php'; ?>
