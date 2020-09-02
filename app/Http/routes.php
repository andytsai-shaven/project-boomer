<?php

// 請勿調整順序
require(app_path('Http/Routes/Auth.php'));

Route::group(['middleware' => 'auth'], function () {
    require(app_path('Http/Routes/Reviews.php'));
    require(app_path('Http/Routes/Works.php'));
    require(app_path('Http/Routes/Workflows.php'));
    require(app_path('Http/Routes/ProjectWorks.php'));
    require(app_path('Http/Routes/Checklists.php'));
    require(app_path('Http/Routes/ProjectChecklists.php'));
    require(app_path('Http/Routes/ConstructionDailies.php'));
    require(app_path('Http/Routes/CostEstimations.php'));
    require(app_path('Http/Routes/FaultImprovements.php'));
    require(app_path('Http/Routes/Bids.php'));
    require(app_path('Http/Routes/Materials.php'));
    require(app_path('Http/Routes/Labors.php'));
    require(app_path('Http/Routes/Appliances.php'));
    require(app_path('Http/Routes/Statistics.php'));
    require(app_path('Http/Routes/ProjectMembers.php'));
    require(app_path('Http/Routes/Projects.php'));
    require(app_path('Http/Routes/Subcontractors.php'));
    require(app_path('Http/Routes/Supports.php'));
    require(app_path('Http/Routes/Miscs.php'));
});
