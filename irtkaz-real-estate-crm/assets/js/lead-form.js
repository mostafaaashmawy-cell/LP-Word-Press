/**
 * IRTKAZ REAL ESTATE — FRONTEND LEAD CAPTURE JAVASCRIPT
 */

document.addEventListener('DOMContentLoaded', function () {
    var leadForms = document.querySelectorAll('.irtkaz-ajax-lead-form, #leadCaptureForm');
    var successModal = document.getElementById('successModal');
    var modalCloseBtn = document.getElementById('modalCloseBtn');
    var modalLeadName = document.getElementById('modalLeadName');

    leadForms.forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var wrapper = form.closest('.lead-form-wrapper') || form.parentElement;
            var submitBtn = form.querySelector('button[type="submit"]');
            var formHeader = wrapper.querySelector('.form-header');
            var formSuccessState = wrapper.querySelector('#formSuccessState, .form-success-state');

            var fullName = (form.querySelector('input[name="fullName"]')?.value || '').trim();
            var phoneNumber = (form.querySelector('input[name="phoneNumber"]')?.value || '').trim();
            var countryCode = form.querySelector('select[name="countryCode"]')?.value || '+20';
            var project = form.getAttribute('data-project') || (form.querySelector('input[name="project"]')?.value) || 'City Gate New Cairo';
            var agency = form.getAttribute('data-agency') || (form.querySelector('input[name="agency"]')?.value) || 'Irtkaz';

            if (!fullName) {
                alert(window.irtkaz_ajax?.strings?.error_name || 'Please enter your full name.');
                return;
            }

            if (!phoneNumber) {
                alert(window.irtkaz_ajax?.strings?.error_phone || 'Please enter your phone number.');
                return;
            }

            var originalBtnText = submitBtn ? submitBtn.innerHTML : 'SUBMIT';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = window.irtkaz_ajax?.strings?.submitting || 'Submitting...';
            }

            var postData = new FormData();
            postData.append('action', 'irtkaz_submit_lead');
            postData.append('nonce', window.irtkaz_ajax?.nonce || '');
            postData.append('fullName', fullName);
            postData.append('phoneNumber', phoneNumber);
            postData.append('countryCode', countryCode);
            postData.append('project', project);
            postData.append('agency', agency);
            postData.append('landingPageUrl', window.location.href);

            // Extract UTMs if present in URL
            var urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('utm_source')) postData.append('utm_source', urlParams.get('utm_source'));
            if (urlParams.get('utm_medium')) postData.append('utm_medium', urlParams.get('utm_medium'));
            if (urlParams.get('utm_campaign')) postData.append('utm_campaign', urlParams.get('utm_campaign'));

            var ajaxUrl = window.irtkaz_ajax?.ajax_url || '/wp-admin/admin-ajax.php';

            fetch(ajaxUrl, {
                method: 'POST',
                body: postData,
            })
            .then(function (res) {
                return res.json();
            })
            .then(function (data) {
                if (data.success) {
                    // Hide form and form header
                    form.style.display = 'none';
                    if (formHeader) {
                        formHeader.style.display = 'none';
                    }

                    // Show inline success message
                    if (formSuccessState) {
                        formSuccessState.style.display = 'block';
                    }

                    // Update modal lead name
                    if (modalLeadName) {
                        modalLeadName.textContent = fullName;
                    }

                    // Open success modal
                    if (successModal) {
                        successModal.classList.add('active');
                        successModal.style.display = 'flex';
                    }

                    // Google Analytics / Ads conversion tracking event
                    if (typeof window.gtag === 'function') {
                        window.gtag('event', 'generate_lead', {
                            event_category: 'lead_form',
                            event_label: project + ' Inquiry',
                            value: 1
                        });
                    }

                    form.reset();
                } else {
                    alert(data.data?.message || window.irtkaz_ajax?.strings?.error_gen || 'Submission failed.');
                }
            })
            .catch(function (err) {
                console.error('Lead submission error:', err);
                alert(window.irtkaz_ajax?.strings?.error_gen || 'Something went wrong. Please try again.');
            })
            .finally(function () {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        });
    });

    // Close modal triggers
    if (modalCloseBtn && successModal) {
        modalCloseBtn.addEventListener('click', function () {
            successModal.classList.remove('active');
            successModal.style.display = 'none';
        });
    }

    if (successModal) {
        successModal.addEventListener('click', function (e) {
            if (e.target === successModal) {
                successModal.classList.remove('active');
                successModal.style.display = 'none';
            }
        });
    }
});
