    <footer class="site-footer">
        <div class="footer-top">
            <!-- Brand Column -->
            <div class="footer-col">
                <h4 style="color: #fff; font-size: 1.5rem;"><?php echo __('logo_title'); ?></h4>
                <p style="font-size: 0.9rem; opacity: 0.6; line-height: 1.6; margin-top: 15px;">
                    <?php echo __('footer_about_text'); ?>
                </p>
                <div class="social-footer">
                    <a href="#"><img src="<?php echo $assets_path; ?>images/logos/facebook.png" alt="Facebook"></a>
                    <a href="#"><img src="<?php echo $assets_path; ?>images/logos/linkedin.png" alt="LinkedIn"></a>
                    <a href="#"><img src="<?php echo $assets_path; ?>images/logos/instagram.png" alt="Instagram"></a>
                </div>
            </div>

            <!-- Links Column 1 -->
            <div class="footer-col">
                <h4><?php echo __('footer_institute'); ?></h4>
                <ul>
                    <li><a href="#"><?php echo __('presentation'); ?></a></li>
                    <li><a href="#"><?php echo __('governance'); ?></a></li>
                    <li><a href="#"><?php echo __('nav_partenariats'); ?></a></li>
                    <li><a href="#"><?php echo __('actualites'); ?></a></li>
                </ul>
            </div>

            <!-- Links Column 2 -->
            <div class="footer-col">
                <h4><?php echo __('footer_formation'); ?></h4>
                <ul>
                    <li><a href="#"><?php echo __('footer_link_engineer'); ?></a></li>
                    <li><a href="#"><?php echo __('footer_link_masters'); ?></a></li>
                    <li><a href="#"><?php echo __('footer_link_doctorate'); ?></a></li>
                    <li><a href="#"><?php echo __('footer_link_elearning'); ?></a></li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div class="footer-col">
                <h4><?php echo __('footer_contact'); ?></h4>
                <ul style="opacity: 0.7; font-size: 0.9rem;">
                    <li><?php echo __('footer_addr_city'); ?></li>
                    <li><?php echo __('footer_addr_bp'); ?></li>
                    <li><?php echo __('footer_addr_tel'); ?>: +212 5 37 77 48 59</li>
                    <li><?php echo __('footer_addr_email'); ?>: contact@insea.ac.ma</li>
                </ul>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="footer-bottom">
            &copy; <?php echo date("Y"); ?> <?php echo __('logo_title'); ?> - <?php echo __('logo_subtitle'); ?>. <?php echo __('footer_rights'); ?>
            <br>
            <a href="<?php echo (basename($_SERVER['PHP_SELF']) === 'index.php') ? 'admin/index.php' : '../../admin/index.php'; ?>" style="opacity: 0.3; font-size: 0.7rem; color: var(--white); text-decoration: none; margin-top: 10px; display: inline-block;">Administration</a>
        </div>
    </footer>
</body>
</html>
