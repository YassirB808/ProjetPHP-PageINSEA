<?php include 'header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('partners_title'); ?></h1>
    </section>

    <section style="max-width: 1100px; margin: 60px auto; padding: 0 20px; text-align: center;">
        <p style="font-size: 1.2rem; line-height: 1.8; color: var(--gray-600); max-width: 800px; margin: 0 auto 60px;">
            <?php echo __('partners_desc'); ?>
        </p>

        <!-- NATIONAL PARTNERS -->
        <div style="margin-bottom: 80px;">
            <h2 style="color: var(--insea-green); margin-bottom: 40px; font-weight: 800; border-bottom: 3px solid var(--insea-gold); display: inline-block; padding-bottom: 8px;">
                <?php echo __('partners_nat_title'); ?>
            </h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 50px; align-items: center;">
                <div style="padding: 20px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/partenariats/hcp_logo.png" alt="HCP" style="max-width: 100%; height: auto; max-height: 120px; object-fit: contain;">
                </div>
                <div style="padding: 20px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/partenariats/Logo_BCP.png" alt="BCP" style="max-width: 100%; height: auto; max-height: 120px; object-fit: contain;">
                </div>
                <div style="padding: 20px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/partenariats/Logo_DGCL.png" alt="DGCL" style="max-width: 100%; height: auto; max-height: 120px; object-fit: contain;">
                </div>
            </div>
        </div>

        <!-- INTERNATIONAL PARTNERS -->
        <div>
            <h2 style="color: var(--insea-green); margin-bottom: 40px; font-weight: 800; border-bottom: 3px solid var(--insea-gold); display: inline-block; padding-bottom: 8px;">
                <?php echo __('partners_inter_title'); ?>
            </h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 50px; align-items: center;">
                <div style="padding: 20px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/partenariats/Logo_ENSAIsvg.svg" alt="ENSAI" style="max-width: 100%; height: auto; max-height: 120px; object-fit: contain;">
                </div>
                <div style="padding: 20px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/partenariats/UCLouvain_Logo.png" alt="UCLouvain" style="max-width: 100%; height: auto; max-height: 120px; object-fit: contain;">
                </div>
                <div style="padding: 20px; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    <img src="../images/partenariats/UNFPA_logo.svg" alt="UNFPA" style="max-width: 100%; height: auto; max-height: 120px; object-fit: contain;">
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
