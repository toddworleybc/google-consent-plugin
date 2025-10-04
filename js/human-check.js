document.addEventListener('DOMContentLoaded', function() {
            let scrolled = false;
            let interacted = false;
            let stayed5sec = false;
            let passedTest = false;

            function checkConditions() {
                if (scrolled && interacted && stayed5sec && !passedTest) {
                    document.cookie = 'EbdHumanTest=passed; path=/; max-age=300'; // lasts 1 day
                    passedTest = true;
                    setTimeout(() => { passedTest = false; }, 180000); // reset after 1 day
                }
            }

            // Condition 1: Scroll more than 100px
            window.addEventListener('scroll', function() {
                
                if (window.scrollY > 100) {
                    
                    if(document.cookie.includes("EbdHumanTest=passed")) return;
                    console.log("working");
                    if(!scrolled) scrolled = true;
                    
                    checkConditions();
                }
            });

            // Condition 2: Mouse movement (desktop)
            window.addEventListener('mousemove', function() {
                if (!interacted) {
                    interacted = true;
                    checkConditions();
                }
            });

            // Condition 2 (alt): Touch interaction (mobile)
            window.addEventListener('touchstart', function() {
                if (!interacted) {
                    interacted = true;
                    checkConditions();
                }
            });

            window.addEventListener('touchmove', function() {
                if (!interacted) {
                    interacted = true;
                    checkConditions();
                }
            });

            // Condition 3: Stayed on page at least 5 seconds
            setTimeout(function() {
                stayed5sec = true;
                checkConditions();
            }, 5000);

        });