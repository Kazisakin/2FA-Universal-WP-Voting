<?php

function display_fluentform_submissions() {
    $form_id = 4; 

    
    $vote_counts = [
        'President' => [],
        'Vice President' => [],
        'General Secretary' => [],
        'Treasurer' => [],
        'Web Master' => []
    ];

   
    $submissions = FluentForm\App\Models\Submission::where('form_id', $form_id)
                    ->where('status', '!=', 'trashed') 
                    ->get();

    if (!empty($submissions)) {
        echo '<table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
        echo '<thead><tr><th>Name</th><th>Email</th><th>UNB Student ID</th><th>President</th><th>Vice President</th><th>General Secretary</th><th>Treasurer</th><th>Web Master</th></tr></thead>';
        echo '<tbody>';

        foreach ($submissions as $submission) {
            
            $form_data = json_decode($submission->response, true);

            // Extract fields and handle missing values
            $name = isset($form_data['names']['first_name']) && isset($form_data['names']['last_name']) 
                ? esc_html($form_data['names']['first_name']) . ' ' . esc_html($form_data['names']['last_name']) 
                : 'N/A';
            $email = isset($form_data['email']) ? esc_html($form_data['email']) : 'N/A';
            $student_id = isset($form_data['numeric_field']) ? esc_html($form_data['numeric_field']) : 'N/A';
            $president_vote = isset($form_data['input_radio']) ? esc_html($form_data['input_radio']) : 'N/A';
            $vp_vote = isset($form_data['input_radio_3']) ? esc_html($form_data['input_radio_3']) : 'N/A';
            $general_secretary_vote = isset($form_data['input_radio_2']) ? esc_html($form_data['input_radio_2']) : 'N/A';
            $treasurer_vote = isset($form_data['input_radio_1']) ? esc_html($form_data['input_radio_1']) : 'N/A';
            $web_master_vote = isset($form_data['input_radio_4']) ? esc_html($form_data['input_radio_4']) : 'N/A';

            
            if (!empty($president_vote)) {
                $vote_counts['President'][$president_vote] = isset($vote_counts['President'][$president_vote]) 
                    ? $vote_counts['President'][$president_vote] + 1 
                    : 1;
            }

            if (!empty($vp_vote)) {
                $vote_counts['Vice President'][$vp_vote] = isset($vote_counts['Vice President'][$vp_vote]) 
                    ? $vote_counts['Vice President'][$vp_vote] + 1 
                    : 1;
            }

            if (!empty($general_secretary_vote)) {
                $vote_counts['General Secretary'][$general_secretary_vote] = isset($vote_counts['General Secretary'][$general_secretary_vote]) 
                    ? $vote_counts['General Secretary'][$general_secretary_vote] + 1 
                    : 1;
            }

            if (!empty($treasurer_vote)) {
                $vote_counts['Treasurer'][$treasurer_vote] = isset($vote_counts['Treasurer'][$treasurer_vote]) 
                    ? $vote_counts['Treasurer'][$treasurer_vote] + 1 
                    : 1;
            }

            if (!empty($web_master_vote)) {
                $vote_counts['Web Master'][$web_master_vote] = isset($vote_counts['Web Master'][$web_master_vote]) 
                    ? $vote_counts['Web Master'][$web_master_vote] + 1 
                    : 1;
            }

           
            echo '<tr>';
            echo '<td>' . $name . '</td>';
            echo '<td>' . $email . '</td>';
            echo '<td>' . $student_id . '</td>';
            echo '<td>' . $president_vote . '</td>';
            echo '<td>' . $vp_vote . '</td>';
            echo '<td>' . $general_secretary_vote . '</td>';
            echo '<td>' . $treasurer_vote . '</td>';
            echo '<td>' . $web_master_vote . '</td>';
            echo '</tr>';
        }

        echo '</tbody></table>';
    } else {
        echo 'No submissions found.';
    }

    
    if (!empty($vote_counts)) {
        echo '<table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">';
        echo '<thead><tr><th>Position</th><th>Candidate</th><th>Votes</th></tr></thead>';
        echo '<tbody>';
        
        foreach ($vote_counts as $position => $candidates) {
            foreach ($candidates as $candidate => $votes) {
                echo '<tr>';
                echo '<td>' . esc_html($position) . '</td>';
                echo '<td>' . esc_html($candidate) . '</td>';
                echo '<td>' . $votes . '</td>';
                echo '</tr>';
            }
        }

        echo '</tbody></table>';
    }
}

add_shortcode('show_fluentform_submissions', 'display_fluentform_submissions');
