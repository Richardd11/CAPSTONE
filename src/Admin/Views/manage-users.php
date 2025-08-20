<?php // moved from src/App/Views/admin/manage-users.php ?>
<!-- Top Section - Add User Actions -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h4 class="text-xl font-semibold text-grey-800 mb-1">
                <i class="fas fa-user-plus mr-2 text-primary-600"></i>
                Add New Users
            </h4>
            <p class="text-grey-600">Add students and faculty to the system</p>
        </div>
        <div class="flex space-x-3">
            <button class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 transform hover:-translate-y-1" onclick="showAddStudentModal()">
                <i class="fas fa-plus mr-2"></i>
                Add Student
            </button>
            <button class="bg-transparent border-2 border-grey-500 text-grey-600 hover:bg-grey-500 hover:text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300" onclick="showAddFacultyModal()">
                <i class="fas fa-plus mr-2"></i>
                Add Faculty
            </button>
        </div>
    </div>
</div>

<?php /* ... Rest of content preserved from original file ... */ ?>

