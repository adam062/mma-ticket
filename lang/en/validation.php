<?php

return [
    'required' => 'The :attribute field is required.',
    'accepted' => 'The :attribute must be accepted.',
    'email' => 'The :attribute field must be a valid email address.',
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
    ],
    'min' => [
        'string' => 'The :attribute must be at least :min characters.',
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
    ],
    'image' => 'The :attribute must be an image.',
    'mimes' => 'The :attribute must be a file of type: :values.',
    'integer' => 'The :attribute must be an integer.',
    'numeric' => 'The :attribute must be a number.',
    'in' => 'The selected :attribute is invalid.',
    'exists' => 'The selected :attribute is invalid.',
    'size' => [
        'string' => 'The :attribute must be :size characters.',
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
    ],
    'string' => 'The :attribute must be a string.',
    'unique' => 'The :attribute has already been taken.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'date' => 'The :attribute is not a valid date.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'uploaded' => 'The :attribute failed to upload.',
];
