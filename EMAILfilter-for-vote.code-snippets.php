<?php

function custom_fluentform_email_validation_4($errors, $data, $form) {
    error_log('FluentForm validation hook triggered'); 

    
    if (intval($form->id) !== 4) {
        error_log('Form ID did not match or ID not found. Found Form ID: ' . $form->id);
        return $errors; 
    }

    
    $allowed_emails = array_map('strtolower', array(
        'EMAILS',
        
    ));

    $submitted_email = '';

    if (isset($data['email'])) {
        $submitted_email = strtolower(trim($data['email']));
        error_log('Submitted email: ' . $submitted_email); 
    } else {
        error_log('Email field is missing.');
    }

    if (empty($submitted_email)) {
        error_log('Email is missing or not found');
        $errors['email'] = __('Email field is missing.', 'fluentform');
    } elseif (!in_array($submitted_email, $allowed_emails)) {
        error_log('Email is not in the allowed list. Submitted: ' . $submitted_email);
        $errors['email'] = __('Sorry, your email is not allowed to submit this form.', 'fluentform');
    } else {
        error_log('Email is in the allowed list');
    }

    if (!empty($errors)) {
        error_log('Errors: ' . print_r($errors, true));
    }

    return $errors; 
}

add_filter('fluentform/validation_errors', 'custom_fluentform_email_validation_4', 10, 3);
