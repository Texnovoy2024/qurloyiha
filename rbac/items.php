<?php

return [
    'createProblem' => [
        'type' => 2,
        'description' => 'Create a problem challenge',
    ],
    'editProblem' => [
        'type' => 2,
        'description' => 'Edit any problem challenge',
    ],
    'editOwnProblem' => [
        'type' => 2,
        'description' => 'Edit own problem challenge',
        'ruleName' => 'isAuthor',
        'children' => [
            'editProblem',
        ],
    ],
    'deleteProblem' => [
        'type' => 2,
        'description' => 'Delete any problem challenge',
    ],
    'deleteOwnProblem' => [
        'type' => 2,
        'description' => 'Delete own problem challenge',
        'ruleName' => 'isAuthor',
        'children' => [
            'deleteProblem',
        ],
    ],
    'submitProposal' => [
        'type' => 2,
        'description' => 'Submit a solution proposal',
    ],
    'editProposal' => [
        'type' => 2,
        'description' => 'Edit any solution proposal',
    ],
    'editOwnProposal' => [
        'type' => 2,
        'description' => 'Edit own solution proposal',
        'ruleName' => 'isAuthor',
        'children' => [
            'editProposal',
        ],
    ],
    'deleteProposal' => [
        'type' => 2,
        'description' => 'Delete any solution proposal',
    ],
    'deleteOwnProposal' => [
        'type' => 2,
        'description' => 'Delete own solution proposal',
        'ruleName' => 'isAuthor',
        'children' => [
            'deleteProposal',
        ],
    ],
    'adminAccess' => [
        'type' => 2,
        'description' => 'Admin panel reporting and moderation actions',
    ],
    'scientist' => [
        'type' => 1,
        'children' => [
            'submitProposal',
            'editOwnProposal',
            'deleteOwnProposal',
        ],
    ],
    'company' => [
        'type' => 1,
        'children' => [
            'createProblem',
            'editOwnProblem',
            'deleteOwnProblem',
        ],
    ],
    'admin' => [
        'type' => 1,
        'children' => [
            'adminAccess',
            'editProblem',
            'deleteProblem',
            'editProposal',
            'deleteProposal',
            'company',
            'scientist',
        ],
    ],
];
