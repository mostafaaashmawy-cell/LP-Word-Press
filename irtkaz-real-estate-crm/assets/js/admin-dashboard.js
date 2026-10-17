/**
 * IRTKAZ REAL ESTATE CRM — ADMIN DASHBOARD JAVASCRIPT
 */

jQuery(document).ready(function($) {
    'use strict';

    // 1. Live Status Change
    $('.irtkaz-status-select').on('change', function() {
        var $select = $(this);
        var leadId = $select.data('id');
        var newStatus = $select.val();

        // Update class for styling
        $select.removeClass('irtkaz-status-new irtkaz-status-contacted irtkaz-status-follow_up irtkaz-status-qualified irtkaz-status-won irtkaz-status-lost');
        $select.addClass('irtkaz-status-' + newStatus);

        $select.prop('disabled', true);

        $.ajax({
            url: irtkaz_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'irtkaz_update_lead_status',
                nonce: irtkaz_admin.nonce,
                id: leadId,
                status: newStatus
            },
            success: function(response) {
                $select.prop('disabled', false);
            },
            error: function() {
                $select.prop('disabled', false);
                alert('Failed to update lead status. Please try again.');
            }
        });
    });

    // 2. Open Notes Modal
    $('.btn-notes').on('click', function(e) {
        e.preventDefault();
        var leadId = $(this).data('id');
        var notes = $(this).data('notes') || '';

        $('#modal-lead-id').val(leadId);
        $('#modal-lead-notes').val(notes);
        $('#irtkaz-notes-modal').fadeIn(150);
    });

    // Close Modal
    $('.irtkaz-modal-close, .irtkaz-modal-overlay').on('click', function() {
        $('#irtkaz-notes-modal').fadeOut(150);
    });

    // Save Notes
    $('#btn-save-notes').on('click', function() {
        var leadId = $('#modal-lead-id').val();
        var notes = $('#modal-lead-notes').val();
        var $btn = $(this);

        $btn.prop('disabled', true).text('Saving...');

        $.ajax({
            url: irtkaz_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'irtkaz_update_lead_notes',
                nonce: irtkaz_admin.nonce,
                id: leadId,
                notes: notes
            },
            success: function(response) {
                $btn.prop('disabled', false).text('Save Notes');
                // Update button data-notes attribute
                $('.btn-notes[data-id="' + leadId + '"]').data('notes', notes);
                $('#irtkaz-notes-modal').fadeOut(150);
            },
            error: function() {
                $btn.prop('disabled', false).text('Save Notes');
                alert('Failed to save notes.');
            }
        });
    });

    // 3. Delete Lead
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        if (!confirm(irtkaz_admin.strings.confirm_delete)) {
            return;
        }

        var leadId = $(this).data('id');
        var $row = $('#irtkaz-lead-row-' + leadId);

        $.ajax({
            url: irtkaz_admin.ajax_url,
            type: 'POST',
            data: {
                action: 'irtkaz_delete_lead',
                nonce: irtkaz_admin.nonce,
                id: leadId
            },
            success: function(response) {
                $row.fadeOut(200, function() {
                    $(this).remove();
                });
            },
            error: function() {
                alert('Failed to delete lead.');
            }
        });
    });
});
