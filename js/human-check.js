document.addEventListener('DOMContentLoaded', function() {
            let scrolled = false;
            let interacted = false;
            let stayed5sec = false;
            let passedTest = false;

            function checkConditions() {
                if (scrolled && interacted && stayed5sec && !passedTest) {
                    document.cookie = 'EbdHumanTest=passed; path=/; max-age=86400'; // lasts 1 day
                    passedTest = true;
                }
            }

            // Condition 1: Scroll more than 100px
            window.addEventListener('scroll', function() {
                if (!scrolled && window.scrollY > 100) {
                    scrolled = true;
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