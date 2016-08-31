# CiviCRM Contact.subscribe API

A CiviCRM extension that provides a single API action to create a contact, add the contact to a specific group, and send a confirmation email.


## Usage:
The Contact.subscribe API action accepts all parameters supported by Contact.create,
and these additional parameters:

* gid ("Group ID"): Required; the CiviCRM group to which the contact should be added.
* template_id ("Message Template ID"): Optional; system ID of the CiviCRM message
template to be used to generate the confirmation email.

If the submitted parameters exactly match exactly one existing contact, that
contact will be used; otherwise, a new contact will be created.

See it in action in your site's CiviCRM API explorer (e.g., http://example.com/civicrm/api/explorer).
Just select Entity = "Contact" and Action = "Subscribe".

