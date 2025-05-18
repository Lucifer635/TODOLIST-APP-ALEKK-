<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$search = isset($_GET['search']) ? $_GET['search'] : null;
$tasks = getUserTasks($_SESSION['user_id'], $pdo, $search);
$tasks = checkLateTasks($tasks);
?>

<?php include 'includes/header.php'; ?>


<div class="row mb-4">
    <div class="col-md-6">
        <h2>Your Tasks</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="add_task.php" class="btn btn-primary">
            <i class="bi bi-plus"></i> Add Task
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12">
        <form method="GET" class="row g-3">
            <div class="col-md-8">
                <input type="text" class="form-control" name="search" placeholder="Search tasks..." value="<?= htmlspecialchars($search ?? '') ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            <div class="col-md-2">
                <?php if ($search): ?>
                    <a href="index.php" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<?php if (empty($tasks)): ?>
    <div class="alert alert-info">No tasks found. Add your first task!</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Deadline</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                    <tr id="row-<?= $task['id'] ?>" class="<?= $task['is_late'] ? 'table-danger' : ($task['status'] == 'completed' ? 'table-success' : '') ?>">
                        <td>
                            <form action="complete_task.php" method="POST" class="d-inline">
                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                <input type="hidden" name="status" value="<?= $task['status'] == 'completed' ? 'pending' : 'completed' ?>">
                                <button type="submit" class="btn btn-sm btn-link">
                                    <?php if ($task['status'] == 'completed'): ?>
                                        <span style="color: #198754; font-weight: bold;">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </span>
                                    <?php else: ?>
                                        <span>
                                            <i class="bi bi-circle text-secondary"></i>
                                        </span>
                                    <?php endif; ?>
                                </button>
                            </form>
                        </td>
                        <td>
                            <?php if ($task['status'] == 'completed'): ?>
                                <span style="text-decoration: line-through;"><?= htmlspecialchars($task['title']) ?></span>
                            <?php else: ?>
                                <?= htmlspecialchars($task['title']) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($task['description']) ?></td>
                        <td>
                            <?= date('M j, Y H:i', strtotime($task['deadline'])) ?>
                            <div id="timer-<?= $task['id'] ?>" class="small text-muted"></div>
                            <span id="late-badge-<?= $task['id'] ?>">
                                <?php if ($task['status'] == 'completed'): ?>
                                    <!-- Tidak tampilkan badge apapun jika sudah completed -->
                                <?php elseif ($task['is_late']): ?>
                                    <span class="badge bg-danger text-white" style="background-color:#dc3545 !important;">TERLAMBAT</span>
                                <?php endif; ?>
                            </span>
                            <script>
                                (function() {
                                    var deadline = new Date("<?= date('Y-m-d H:i:s', strtotime($task['deadline'])) ?>").getTime();
                                    var timerId = "timer-<?= $task['id'] ?>";
                                    var badgeId = "late-badge-<?= $task['id'] ?>";
                                    var rowId = "row-<?= $task['id'] ?>";
                                    function updateTimer() {
                                        var now = new Date().getTime();
                                        var distance = deadline - now;
                                        var isCompleted = "<?= $task['status'] ?>" === "completed";
                                        if (isCompleted) {
                                            // Hilangkan badge TERLAMBAT jika sudah completed
                                            var badge = document.getElementById(badgeId);
                                            if (badge) badge.innerHTML = '';
                                            var row = document.getElementById(rowId);
                                            if (row) {
                                                row.classList.remove('table-danger');
                                                row.classList.add('table-success');
                                            }
                                            return;
                                        }
                                        if (distance <= 0) {
                                            document.getElementById(timerId).innerHTML = "Waktu habis";
                                            // Tampilkan badge TERLAMBAT jika belum ada
                                            var badge = document.getElementById(badgeId);
                                            if (badge && badge.innerHTML.trim() === "") {
                                                badge.innerHTML = '<span class="badge bg-danger text-white" style="background-color:#dc3545 !important;">TERLAMBAT</span>';
                                            }
                                            // Tambahkan background merah pada baris
                                            var row = document.getElementById(rowId);
                                            if (row && !row.classList.contains('table-danger')) {
                                                row.classList.remove('table-success');
                                                row.classList.add('table-danger');
                                            }
                                            return;
                                        }
                                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                        document.getElementById(timerId).innerHTML =
                                            days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
                                        setTimeout(updateTimer, 1000);
                                    }
                                    updateTimer();
                                })();
                            </script>
                        </td>
                        <td>
                            <a href="edit_task.php?id=<?= $task['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="delete_task.php" method="POST" class="d-inline">
                                <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>