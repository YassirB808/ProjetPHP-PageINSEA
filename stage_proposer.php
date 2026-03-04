<?php include 'components/PHP/header.php'; ?>

<main class="main-content">
    <section class="page-banner" style="background: var(--insea-green); color: var(--white); padding: 60px 5%; text-align: center;">
        <h1 style="font-size: 2.5rem; font-weight: 800;"><?php echo __('stage_form_title'); ?></h1>
    </section>

    <section style="max-width: 800px; margin: 60px auto; padding: 0 20px;">
        <div style="background: var(--white); padding: 40px; border-radius: 15px; box-shadow: 0 15px 40px rgba(0,0,0,0.1); border: 1px solid var(--gray-200);">
            <p style="text-align: center; color: var(--gray-600); margin-bottom: 40px; font-size: 1.1rem;">
                <?php echo __('stage_form_desc'); ?>
            </p>

            <form action="#" method="POST" style="display: grid; gap: 25px;">
                <div>
                    <label style="display: block; margin-bottom: 10px; font-weight: 700; color: var(--gray-800);"><?php echo __('form_company'); ?> *</label>
                    <input type="text" required style="width: 100%; padding: 14px; border: 2px solid var(--gray-200); border-radius: 8px; font-size: 1rem; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--insea-green)'" onblur="this.style.borderColor='var(--gray-200)'">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 700; color: var(--gray-800);"><?php echo __('form_contact_name'); ?> *</label>
                        <input type="text" required style="width: 100%; padding: 14px; border: 2px solid var(--gray-200); border-radius: 8px; font-size: 1rem; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--insea-green)'" onblur="this.style.borderColor='var(--gray-200)'">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 700; color: var(--gray-800);"><?php echo __('form_email'); ?> *</label>
                        <input type="email" required style="width: 100%; padding: 14px; border: 2px solid var(--gray-200); border-radius: 8px; font-size: 1rem; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--insea-green)'" onblur="this.style.borderColor='var(--gray-200)'">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 700; color: var(--gray-800);"><?php echo __('form_type'); ?> *</label>
                        <select required style="width: 100%; padding: 14px; border: 2px solid var(--gray-200); border-radius: 8px; font-size: 1rem; background: white; cursor: pointer; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--insea-green)'" onblur="this.style.borderColor='var(--gray-200)'">
                            <option value=""><?php echo __('form_select_default'); ?></option>
                            <option value="initiation"><?php echo __('stage_type_1_title'); ?></option>
                            <option value="application"><?php echo __('stage_type_2_title'); ?></option>
                            <option value="pfe"><?php echo __('stage_type_3_title'); ?></option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 10px; font-weight: 700; color: var(--gray-800);"><?php echo __('form_duration'); ?> *</label>
                        <select required style="width: 100%; padding: 14px; border: 2px solid var(--gray-200); border-radius: 8px; font-size: 1rem; background: white; cursor: pointer; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--insea-green)'" onblur="this.style.borderColor='var(--gray-200)'">
                            <option value=""><?php echo __('form_select_default'); ?></option>
                            <option value="1_month">1 <?php echo __('form_month'); ?></option>
                            <option value="2_months">2 <?php echo __('form_month'); ?></option>
                            <option value="3_months">3 <?php echo __('form_month'); ?></option>
                            <option value="4_months">4 <?php echo __('form_month'); ?></option>
                            <option value="6_months">6 <?php echo __('form_month'); ?></option>
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display: block; margin-bottom: 10px; font-weight: 700; color: var(--gray-800);"><?php echo __('form_desc'); ?> *</label>
                    <textarea required rows="5" style="width: 100%; padding: 14px; border: 2px solid var(--gray-200); border-radius: 8px; font-size: 1rem; resize: vertical; transition: border-color 0.3s;" onfocus="this.style.borderColor='var(--insea-green)'" onblur="this.style.borderColor='var(--gray-200)'"></textarea>
                </div>

                <button type="submit" class="btn-outline" style="background: var(--insea-green); color: white; border: none; padding: 18px; cursor: pointer; font-size: 1.1rem; font-weight: 800; margin-top: 10px; border-radius: 8px; transition: 0.3s;">
                    <?php echo __('form_submit'); ?>
                </button>
            </form>
        </div>
    </section>
</main>

<?php include 'components/PHP/footer.php'; ?>
