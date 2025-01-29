<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CriticalCareController extends Controller
{
    public function index()
    {
        $videos = [
            [
                'title' => 'How to use a Tourniquet to Control Life-Threatening Bleeding',
                'url' => 'https://www.youtube.com/embed/k98ilfQmUWw',
                'description' => 'A step-by-step guide by the American Red Cross on properly applying a tourniquet.'
            ],
            [
                'title' => 'Stop the Bleed: How to use a tourniquet',
                'url' => 'https://www.youtube.com/embed/LkO4QguSBB0',
                'description' => 'Instructional video by Monongalia County Health Department on using a tourniquet.'
            ],
            [
                'title' => 'Chest Seals/Sucking Chest Wounds',
                'url' => 'https://www.youtube.com/embed/MXTZbF4bAfE',
                'description' => 'PrepMedic explains the use of chest seals for sucking chest wounds.'
            ],
            [
                'title' => 'Sucking Chest Wound (Open Pneumothorax) First Aid Treatment',
                'url' => 'https://www.youtube.com/embed/kv8Z0ZsOSJs',
                'description' => 'MedCram provides a clear explanation of first aid treatment for open pneumothorax.'
            ]
        ];

        return view('critical-care.index', compact('videos'));
    }
}

