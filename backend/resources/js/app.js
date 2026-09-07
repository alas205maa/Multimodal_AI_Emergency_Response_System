// Multimodal AI Emergency Response System - Interactive Behaviors

document.addEventListener('DOMContentLoaded', () => {
    initGeolocation();
    initImageUploader();
    initModalSystem();
    initEmergencyReviewSync();
    initReportSubmissionState();
    initResponderLiveClock();
    initSidebarToggle();
});

/**
 * HTML5 Geolocation handling with detection states.
 */
function initGeolocation() {
    const geoBtn = document.getElementById('detect-location-btn');
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const statusText = document.getElementById('location-status-text');
    const statusBadge = document.getElementById('location-status-badge');
    const reviewCoords = document.getElementById('review-location-coords');

    if (!geoBtn || !latInput || !lngInput) return;

    geoBtn.addEventListener('click', () => {
        if (!navigator.geolocation) {
            updateLocationStatus('unavailable', 'Location unavailable on this device/browser');
            return;
        }

        updateLocationStatus('detecting', 'Detecting your precise coordinates...');
        geoBtn.disabled = true;

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);

                latInput.value = lat;
                lngInput.value = lng;

                updateLocationStatus('detected', `Coordinates: ${lat}, ${lng} (±${Math.round(position.coords.accuracy)}m)`);
                geoBtn.disabled = false;

                if (reviewCoords) {
                    reviewCoords.textContent = `${lat}, ${lng}`;
                }

                // Trigger change event for review sync
                latInput.dispatchEvent(new Event('input'));
            },
            (error) => {
                geoBtn.disabled = false;
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        updateLocationStatus('denied', 'Location permission denied by user');
                        break;
                    case error.POSITION_UNAVAILABLE:
                        updateLocationStatus('unavailable', 'Location information is currently unavailable');
                        break;
                    case error.TIMEOUT:
                        updateLocationStatus('unavailable', 'Location request timed out. Please retry');
                        break;
                    default:
                        updateLocationStatus('unavailable', 'Failed to detect location');
                }
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });

    function updateLocationStatus(state, message) {
        if (!statusText) return;
        statusText.textContent = message;

        if (statusBadge) {
            statusBadge.className = 'inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full';
            if (state === 'detecting') {
                statusBadge.classList.add('bg-amber-50', 'text-amber-700', 'border', 'border-amber-200');
            } else if (state === 'detected') {
                statusBadge.classList.add('bg-emerald-50', 'text-emerald-700', 'border', 'border-emerald-200');
            } else if (state === 'denied' || state === 'unavailable') {
                statusBadge.classList.add('bg-rose-50', 'text-rose-700', 'border', 'border-rose-200');
            }
        }
    }
}

/**
 * Image Uploader with Drag-and-Drop, File select, and Live Preview.
 */
function initImageUploader() {
    const dropZone = document.getElementById('image-drop-zone');
    const fileInput = document.getElementById('image-file-input');
    const previewContainer = document.getElementById('image-preview-container');
    const previewImg = document.getElementById('image-preview-img');
    const uploadPlaceholder = document.getElementById('image-upload-placeholder');
    const removeBtn = document.getElementById('remove-image-btn');
    const replaceBtn = document.getElementById('replace-image-btn');
    const reviewImageStatus = document.getElementById('review-image-status');

    if (!dropZone || !fileInput) return;

    // Drag & Drop events
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.add('border-teal-600', 'bg-teal-50/20');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
            dropZone.classList.remove('border-teal-600', 'bg-teal-50/20');
        });
    });

    dropZone.addEventListener('drop', (e) => {
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            handleFileSelect(fileInput.files[0]);
        }
    });

    fileInput.addEventListener('change', () => {
        if (fileInput.files && fileInput.files[0]) {
            handleFileSelect(fileInput.files[0]);
        }
    });

    if (removeBtn) {
        removeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.value = '';
            if (previewContainer) previewContainer.classList.add('hidden');
            if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
            if (reviewImageStatus) reviewImageStatus.textContent = 'No image attached';
        });
    }

    if (replaceBtn) {
        replaceBtn.addEventListener('click', (e) => {
            e.preventDefault();
            fileInput.click();
        });
    }

    function handleFileSelect(file) {
        if (!file.type.startsWith('image/')) {
            alert('Please select a valid image file (PNG, JPG, JPEG, WEBP).');
            return;
        }

        const reader = new FileReader();
        reader.onload = (e) => {
            if (previewImg) previewImg.src = e.target.result;
            if (uploadPlaceholder) uploadPlaceholder.classList.add('hidden');
            if (previewContainer) previewContainer.classList.remove('hidden');
            if (reviewImageStatus) reviewImageStatus.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
        };
        reader.readAsDataURL(file);
    }
}

/**
 * Modal System for confirm, alerts, and quick details dialogs.
 */
function initModalSystem() {
    const triggers = document.querySelectorAll('[data-modal-target]');
    const closeButtons = document.querySelectorAll('[data-modal-close]');

    triggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const modalId = trigger.getAttribute('data-modal-target');
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        });
    });

    closeButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault();
            const modal = button.closest('.modal-backdrop');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const visibleModals = document.querySelectorAll('.modal-backdrop:not(.hidden)');
            visibleModals.forEach(modal => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            });
        }
    });
}

/**
 * Sync Report Form Inputs with Live Review Area.
 */
function initEmergencyReviewSync() {
    const descInput = document.getElementById('description');
    const reviewDesc = document.getElementById('review-description');

    if (descInput && reviewDesc) {
        descInput.addEventListener('input', () => {
            reviewDesc.textContent = descInput.value.trim() || 'No description provided yet...';
        });
    }
}

/**
 * Report Submission Loading State.
 */
function initReportSubmissionState() {
    const form = document.getElementById('emergency-report-form');
    const submitBtn = document.getElementById('submit-report-btn');
    const loadingOverlay = document.getElementById('submission-loading-overlay');

    if (form && submitBtn) {
        form.addEventListener('submit', () => {
            submitBtn.disabled = true;
            if (loadingOverlay) {
                loadingOverlay.classList.remove('hidden');
            }
        });
    }
}

/**
 * Real-time clock for Responder Operations Center.
 */
function initResponderLiveClock() {
    const clockElement = document.getElementById('responder-live-clock');
    if (!clockElement) return;

    function updateTime() {
        const now = new Date();
        clockElement.textContent = now.toLocaleTimeString('en-US', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    updateTime();
    setInterval(updateTime, 1000);
}

/**
 * Responsive sidebar toggle for Responder portal.
 */
function initSidebarToggle() {
    const toggleBtn = document.getElementById('sidebar-toggle-btn');
    const sidebar = document.getElementById('responder-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');

    if (!toggleBtn || !sidebar) return;

    toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        if (backdrop) backdrop.classList.toggle('hidden');
    });

    if (backdrop) {
        backdrop.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        });
    }
}
