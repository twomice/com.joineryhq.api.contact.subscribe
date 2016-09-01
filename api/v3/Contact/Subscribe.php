<?php

/**
 * Contact.Subscribe API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_contact_subscribe_spec(&$spec) {
  // Group ID is required.
  $spec['gid'] = array(
    'api.required' => 1,
    'title' => 'Group ID',
    'description' => 'System ID of the CiviCRM group to add the contact to',
    'type' => CRM_Utils_Type::T_INT,
  );
  // Message template ID is optional.
  $spec['template_id'] = array(
    'api.required' => 0,
    'title' => 'Message Template ID',
    'description' => 'System ID of the message template for the confirmation email',
    'type' => CRM_Utils_Type::T_INT,
  );
  // Also accept all parameters from contact.create.
  _civicrm_api3_contact_create_spec($spec);
}

/**
 * Contact.Subscribe API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_contact_subscribe($params) {
  if (empty($params['gid'])) {
    throw new API_Exception('Missing or empty required parameter: gid', 'mandatory_missing');
  } 
  else {
    $contact_result = NULL;

    // Wrap all this in a transaction -- if anything fails, we don't want the
    // contact hanging around subscribed to nothing, for example.
    CRM_Core_Transaction::create()->run(function($tx) use ($params, &$contact_result) {
      
      $template_id = $params['template_id'];
      $group_id = $params['gid'];
      unset($params['mailing_template_id']);
      unset($params['gid']);

      // Create the contact, or find the one identical existing contact.
      $contact_result = _civicrm_api3_contact_subscribe_create_contact($params);

      // Add contact to group.
      $params = array(
        'contact_id' => $contact_result['id'],
        'group_id' => $group_id,
      );
      try {
        $result = civicrm_api3('group_contact', 'create', $params);
      }
      catch (CiviCRM_API3_Exception $e) {
        throw new API_Exception('Error creating group_contact: '. $e->getMessage(), 'api_error');
      }

      // Send the email, if template_id is provided.
      if (!empty($template_id)) {
        $params = array(
          'sequential' => 1,
          'contact_id' => $contact_result['id'],
          'template_id' => $template_id,
        );
        try {
          $result = civicrm_api3('Email', 'send', $params);
        }
        catch (CiviCRM_API3_Exception $e) {
          throw new API_Exception('Error sending confirmation email: '. $e->getMessage(), 'api_error');
        }
      }
    });
    
    // Return the created contact.
    $returnValues = array(
      // Depending on 'sequential' parameter, $contact_result['values'] may
      // be keyed sequentially or not. Use array_shift() to get the first value
      // without knowing the keys.
      $contact_result['id'] => array_shift($contact_result['values']),
    );
    return civicrm_api3_create_success($returnValues, $params, 'Contact', 'subscribe');
  }
}

/**
 * Create a new contact if there's not exactly one identical contact already.
 *
 * @param Array $api_params
 * @return Array, as returned by civicrm_api3().
 */
function _civicrm_api3_contact_subscribe_create_contact($api_params) {
  try {
    $result = civicrm_api3('contact', 'get', $api_params);
  }
  catch (CiviCRM_API3_Exception $e) {
    throw new API_Exception('Error checking for existing contact: '. $e->getMessage(), 'api_error');
  }

  if ($result['count'] != 1) {
    try {
      $result = civicrm_api3('contact', 'create', $api_params);
    }
    catch (CiviCRM_API3_Exception $e) {
      throw new API_Exception('Error creating contact: '. $e->getMessage(), 'api_error');
    }
  }

  return $result;
}