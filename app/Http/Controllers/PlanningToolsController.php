<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PlanningToolsController extends Controller
{
    public function coupleIndex(): View
    {
        return view('tools.planning_tools', [
            'role' => 'couple',
            'page' => 'planning_tools',
        ]);
    }

    public function vendorIndex(): View
    {
        return view('tools.planning_tools', [
            'role' => 'vendor',
            'page' => 'planning_tools',
        ]);
    }
}

