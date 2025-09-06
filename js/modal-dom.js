
  function consentUpdate() {

    const ad_storage = document.getElementById("toggleAdStorage").checked ? 'granted' : 'denied';
    const ad_user_data = document.getElementById("toggleAdUserData").checked ? 'granted' : 'denied';
    const ad_personalization = document.getElementById("toggleAdPersonalization").checked ? 'granted' : 'denied';
    const analytics_storage = document.getElementById("toggleAnalyticsStorage").checked ? 'granted' : 'denied';


    const consentGrantedSettings = {
      'ad_storage': ad_storage,
      'ad_user_data': ad_user_data,
      'ad_personalization': ad_personalization,
      'analytics_storage': analytics_storage
    };
    

    window.ebdConsentGrantedSettings = consentGrantedSettings;

    consentGrantedAdStorage() // update the consent storage

  }
  
 
  // Create privacy modal HTML structure  
const privacyModal = `
    <!-- Modal Overlay -->
    <div id="customModalOverlay" class="ebd-modal-overlay">
      <div class="ebd-modal-content">
        <div class="ebd-modal-header">
          <h2 class="ebd-privacy-policy-header">We Value Your Privacy</h2>
        </div>
        <div class="ebd-modal-body">
          <p style:="padding-bottom: 10px;">${ebd_gc_options.privacy_policy_message}</p>
          <p><a class="ebd-link" href="${ebd_gc_options.privacy_policy_path}" target="_blank">View Privacy Policy</a></p>
        </div>
        <div class="ebd-modal-footer">
          ${ebd_gc_options.force_accept_consent !== '1' ? '<a onclick="window.ebdOpenOptionsModal()" class="ebd-link">Options</a>' : '' }
          

          <button onclick="consentUpdate()" class="ebd-footer-close">Accept</button>
          
        </div>
      </div>
    </div>`;

// Append modal HTML to the body
    document.body.insertAdjacentHTML('beforeend', privacyModal);



   // Create privacy modal HTML structure  
const optionsModal = `
    <!-- Modal Overlay -->
    <div id="optionsModalOverlay" class="ebd-modal-overlay">
      <div class="ebd-modal-content">
        <div class="ebd-modal-header">
          <h2>Choose Your Options</h2>
    
        </div>
        <div class="ebd-modal-body" style="text-align: left;">
            <label>
                <input type="checkbox" id="toggleAdStorage" checked>
                Ad Storage
            </label><br>
            <label>
                <input type="checkbox" id="toggleAdUserData" checked>
                Ad User Data
            </label><br>
            <label>
                <input type="checkbox" id="toggleAdPersonalization" checked>
                Ad Personalization
            </label><br>
            <label>
                <input type="checkbox" id="toggleAnalyticsStorage" checked>
                Analytics Storage
            </label>
        </div>
        <div class="ebd-modal-footer-options">
          <button onclick="consentUpdate()" class="ebd-footer-close">Accept</button>
        </div>
      </div>
    </div>`;

// Append modal HTML to the body
    document.body.insertAdjacentHTML('beforeend', optionsModal);





