<!-- Confirmation Modal HTML -->
<div id="confirmModal" class="modal-overlay">
    <div class="modal-card">
        <div id="modalIcon" class="modal-icon">!</div>
        <h3 id="modalTitle">Confirmation</h3>
        <p id="modalMessage">Êtes-vous sûr de vouloir continuer cette action ?</p>
        <div class="modal-actions">
            <button type="button" class="btn-modal btn-cancel-modal" onclick="hideModal()">Annuler</button>
            <button type="button" id="confirmBtn" class="btn-modal btn-confirm">Confirmer</button>
        </div>
    </div>
</div>

<script>
let modalCallback = null;

function showConfirm(title, message, callback, isWarning = false) {
    const modal = document.getElementById('confirmModal');
    const icon = document.getElementById('modalIcon');
    const confirmBtn = document.getElementById('confirmBtn');
    
    document.getElementById('modalTitle').innerText = title;
    document.getElementById('modalMessage').innerText = message;
    
    if (isWarning) {
        icon.className = 'modal-icon warning';
        icon.innerText = '!';
        confirmBtn.className = 'btn-modal btn-confirm btn-warning';
    } else {
        icon.className = 'modal-icon';
        icon.innerText = '✕';
        confirmBtn.className = 'btn-modal btn-confirm';
    }

    modalCallback = callback;
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('active'), 10);
}

function hideModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.remove('active');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

document.getElementById('confirmBtn').onclick = function() {
    if (modalCallback) modalCallback();
    hideModal();
};

// Global Logout Handler
function confirmLogout(url) {
    showConfirm(
        "Déconnexion", 
        "Voulez-vous vraiment quitter votre session d'administration ?", 
        () => { window.location.href = url; },
        true
    );
}

// Global Delete Handler (Strictly targeting .link-delete)
document.addEventListener('click', function(e) {
    // Look for the closest anchor tag with .link-delete class
    const deleteLink = e.target.closest('.link-delete');
    
    if (deleteLink) {
        e.preventDefault();
        e.stopPropagation();
        
        const href = deleteLink.getAttribute('href');
        showConfirm(
            "Suppression", 
            "Cette action est irréversible. Voulez-vous vraiment supprimer cet élément ?", 
            () => { window.location.href = href; },
            false
        );
    }
}, true);
</script>
