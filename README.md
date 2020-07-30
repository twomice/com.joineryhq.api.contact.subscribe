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

## Support
![screenshot](/images/joinery-logo.png)

Joinery provides services for CiviCRM including custom extension development, training, data migrations, and more. We aim to keep this extension in good working order, and will do our best to respond appropriately to issues reported on its [github issue queue](https://github.com/twomice/com.joineryhq.api.contact.subscribe/issues). In addition, if you require urgent or highly customized improvements to this extension, we may suggest conducting a fee-based project under our standard commercial terms.  In any case, the place to start is the [github issue queue](https://github.com/twomice/com.joineryhq.api.contact.subscribe/issues) -- let us hear what you need and we'll be glad to help however we can.

And, if you need help with any other aspect of CiviCRM -- from hosting to custom development to strategic consultation and more -- please contact us directly via https://joineryhq.com