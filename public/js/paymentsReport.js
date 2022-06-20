var cppPayReport = cppPayReport || {};

cppPayReport = function() {

    /**
     * Add click evnt listener to all send reminder buttons
     */
    
    var addReminderListeners = () => {
        const buttons = document.querySelectorAll('.send-reminder-button');
        
        for (let i = 0; i < buttons.length; i++) {
            buttons[i].addEventListener("click", (event) => {
                sendEmail(event.target.getAttribute('data-contact-id'));
            });
        }
    };
    
    
    /**
     * Ajax request to send reminder email
     * 
     * @param  {number} contactId              
     */
    
    var sendEmail = (contactId) => {
        let params = new URLSearchParams({'contact_id': contactId}).toString();
        let url = '/contact/payment_reminder?' + params;
        
        fetch(url)
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                let message = data.success ? 'Reminder sent' : 'Reminder not sent' + (data.error_message !== '' ? ': ' + data.error_message : '');
                showAlert(message, data.success ? 'success' : 'danger');
            })
            .catch(function(error) {
               showAlert('Reminder not sent', 'danger');
            });
    };
    
    return {
       sendEmail: sendEmail,
       addReminderListeners: addReminderListeners
   };

}();


cppPayReport.addReminderListeners();
