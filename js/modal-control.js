document.addEventListener("DOMContentLoaded", function() {
  const modal = document.getElementById("customModalOverlay");
  const optionsModal = document.getElementById("optionsModalOverlay");
  const modalDelaySeconds = (ebd_gc_control.delay_seconds || 3) * 1000; // Default to 3 seconds if not set
  


  // Show modal after 3 seconds
  setTimeout(() => {
    if(modalOpenCheck()) { // check if modal should be shown
      
       modal.style.display = "flex";
       document.body.style.overflow = "hidden"; // Prevent scrolling
      
    } else {
      
      if(Cookies.get('EbdUserSelectedConsent')) {

        const EbdUserSelectedConsent = JSON.parse(Cookies.get('EbdUserSelectedConsent'));

        window.ebdConsentGrantedSettings = EbdUserSelectedConsent;

        consentGrantedAdStorage(); // update the consent storage

      }

    }
  }, modalDelaySeconds);


  function openOptionsModal() {
    closeModal()
    optionsModal.style.display = "flex";
    document.body.style.overflow = "hidden"; // Prevent scrolling
  }

  function closeOptionsModal() {
     optionsModal.style.display = "none";
    document.body.style.overflow = "auto"; // Restore scrolling
  }

  function modalOpenCheck() {

    if(ebd_gc_control.is_logged_in) return false; // Don't show modal to logged in users
   
    const privacyPolicyPath = ebd_gc_options.privacy_policy_path;


    if(window.location.href.includes(privacyPolicyPath)) {
      return false; // Don't show modal on privacy policy page
    } else {


      // Check if the privacy policy modal should be shown
        return ebd_gc_control.privacy_policy_active === '1' &&
        Cookies.get('EbdPolicyAccepted') !== "Granted" ? true : false;

    }
  }

  // Function to close modal
  function closeModal() {
    modal.style.display = "none";
    document.body.style.overflow = "auto"; // Restore scrolling
  }

  window.ebdOpenOptionsModal = openOptionsModal; // Expose openOptionsModal globally
  window.ebdCloseOptionsModal = closeOptionsModal; // Expose closeOptionsModal globally
  window.ebdCloseModal = closeModal; // Expose closeModal globally


 
});
