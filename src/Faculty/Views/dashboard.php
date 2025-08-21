<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Faculty Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        grey: {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            200: '#e5e7eb',
                            300: '#d1d5db',
                            400: '#9ca3af',
                            500: '#6b7280',
                            600: '#4b5563',
                            700: '#374151',
                            800: '#1f2937',
                            900: '#111827',
                        }
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-grey-50">
    <header class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-6 mb-8">
        <div class="container mx-auto px-4 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    Faculty Dashboard
                </h1>
                <p class="opacity-90">Welcome, <?= htmlspecialchars($faculty['full_name'] ?? 'Faculty') ?></p>
            </div>
            <div>
                <a href="<?= dirname($_SERVER['SCRIPT_NAME']) ?>/admin/logout" class="bg-transparent border-2 border-white text-white px-5 py-2 rounded-full hover:bg-white hover:text-primary-700 transition">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4">
        <!-- Quick stats -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border border-grey-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-grey-500 mb-1">My Classes</p>
                        <p class="text-3xl font-bold text-primary-600">0</p>
                    </div>
                    <div class="bg-primary-50 text-primary-600 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border border-grey-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-grey-500 mb-1">Upcoming Exams</p>
                        <p class="text-3xl font-bold text-primary-600">0</p>
                    </div>
                    <div class="bg-primary-50 text-primary-600 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border border-grey-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-grey-500 mb-1">Pending Reviews</p>
                        <p class="text-3xl font-bold text-primary-600">0</p>
                    </div>
                    <div class="bg-primary-50 text-primary-600 w-12 h-12 rounded-full flex items-center justify-center">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- Quick actions and recent activity -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white rounded-lg shadow p-6 border border-grey-200">
                <h2 class="text-lg font-semibold text-grey-800 mb-4"><i class="fas fa-bolt mr-2 text-primary-600"></i>Quick Actions</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a class="flex items-center justify-between px-4 py-3 rounded-lg border border-grey-200 hover:border-primary-300 hover:bg-primary-50 transition cursor-pointer">
                        <span class="text-grey-700"><i class="fas fa-plus-circle mr-2 text-primary-600"></i>Create Exam</span>
                        <i class="fas fa-angle-right text-grey-400"></i>
                    </a>
                    <a class="flex items-center justify-between px-4 py-3 rounded-lg border border-grey-200 hover:border-primary-300 hover:bg-primary-50 transition cursor-pointer">
                        <span class="text-grey-700"><i class="fas fa-users mr-2 text-primary-600"></i>Manage Classes</span>
                        <i class="fas fa-angle-right text-grey-400"></i>
                    </a>
                    <a class="flex items-center justify-between px-4 py-3 rounded-lg border border-grey-200 hover:border-primary-300 hover:bg-primary-50 transition cursor-pointer">
                        <span class="text-grey-700"><i class="fas fa-file-import mr-2 text-primary-600"></i>Import Questions</span>
                        <i class="fas fa-angle-right text-grey-400"></i>
                    </a>
                    <a class="flex items-center justify-between px-4 py-3 rounded-lg border border-grey-200 hover:border-primary-300 hover:bg-primary-50 transition cursor-pointer">
                        <span class="text-grey-700"><i class="fas fa-chart-line mr-2 text-primary-600"></i>View Reports</span>
                        <i class="fas fa-angle-right text-grey-400"></i>
                    </a>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border border-grey-200">
                <h2 class="text-lg font-semibold text-grey-800 mb-4"><i class="fas fa-clock mr-2 text-primary-600"></i>Recent Activity</h2>
                <p class="text-grey-500">No recent activity yet.</p>
            </div>
        </section>
    </main>
</body>
</html>

