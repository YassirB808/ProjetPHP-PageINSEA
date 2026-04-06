<?php 
include 'header.php'; 

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $company = $_POST['company'] ?? '';
    $contact_name = $_POST['contact_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $type = $_POST['type'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!empty($company) && !empty($contact_name) && !empty($email) && !empty($type) && !empty($duration) && !empty($description)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO internship_proposals (company, contact_name, email, type, duration, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$company, $contact_name, $email, $type, $duration, $description]);
            $message = __('form_success_msg');
            $message_type = 'success';
        } catch (PDOException $e) {
            $message = __('form_error_msg') . $e->getMessage();
            $message_type = 'error';
        }
    } else {
        $message = __('form_fill_all_msg');
        $message_type = 'error';
    }
}
?>

<main class="main-content">
    <section class="page-banner">
        <h1><?php echo __('stage_form_title'); ?></h1>
    </section>

    <section class="content-container-narrow">
        <div class="form-card">
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <p class="page-intro mb-40">
                <?php echo __('stage_form_desc'); ?>
            </p>

            <form action="" method="POST" class="form-grid">
                <div>
                    <label><?php echo __('form_company'); ?> *</label>
                    <input type="text" name="company" required>
                </div>

                <div class="form-row-2">
                    <div>
                        <label><?php echo __('form_contact_name'); ?> *</label>
                        <input type="text" name="contact_name" required>
                    </div>
                    <div>
                        <label><?php echo __('form_email'); ?> *</label>
                        <input type="email" name="email" required>
                    </div>
                </div>

                <div class="form-row-2">
                    <div>
                        <label><?php echo __('form_type'); ?> *</label>
                        <select name="type" required>
                            <option value=""><?php echo __('form_select_default'); ?></option>
                            <option value="initiation"><?php echo __('stage_type_1_title'); ?></option>
                            <option value="application"><?php echo __('stage_type_2_title'); ?></option>
                            <option value="pfe"><?php echo __('stage_type_3_title'); ?></option>
                        </select>
                    </div>
                    <div>
                        <label><?php echo __('form_duration'); ?> *</label>
                        <select name="duration" required>
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
                    <label><?php echo __('form_desc'); ?> *</label>
                    <textarea name="description" required rows="5"></textarea>
                </div>

                <button type="submit" class="btn-form-submit">
                    <?php echo __('form_submit'); ?>
                </button>
            </form>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
