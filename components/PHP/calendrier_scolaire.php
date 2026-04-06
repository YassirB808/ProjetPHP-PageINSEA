<?php 
include 'header.php'; 

$lang_id = get_language_id($pdo, get_lang_code());

$stmt = $pdo->prepare("
    SELECT e.event_date, et.title
    FROM events e
    JOIN events_translations et ON e.id = et.event_id
    WHERE et.language_id = ?
    ORDER BY e.id ASC
");
$stmt->execute([$lang_id]);
$events = $stmt->fetchAll();
?>

<main class="main-content">
    <section class="page-banner">
        <h1><?php echo __('cal_title'); ?></h1>
    </section>

    <section class="content-container">
        <p class="page-intro">
            <?php echo __('cal_desc'); ?>
        </p>

        <div class="form-card" style="overflow: hidden; padding: 0;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: var(--insea-green); color: var(--white);">
                        <th style="padding: 20px; font-weight: 700;"><?php echo __('cal_event'); ?></th>
                        <th style="padding: 20px; font-weight: 700;"><?php echo __('cal_date'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="2" class="text-center" style="padding: 20px; color: var(--gray-500);"><?php echo __('no_events_found'); ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($events as $index => $event): ?>
                            <tr style="border-bottom: 1px solid var(--gray-200); <?php echo $index % 2 != 0 ? 'background: var(--gray-50);' : ''; ?>">
                                <td style="padding: 20px; font-weight: 600;"><?php echo htmlspecialchars($event['title']); ?></td>
                                <td style="padding: 20px;"><?php echo htmlspecialchars($event['event_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
