<!-- ... tu HTML del footer ... -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script personalizado para manejar las acciones -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- LÓGICA PARA GESTIONAR ROLES (SUPERUSUARIO) ---
    const rolSelectors = document.querySelectorAll('.user-role-selector');
    rolSelectors.forEach(selector => {
        selector.addEventListener('change', function() {
            const userId = this.dataset.userId;
            const newRole = this.value;
            
            if (confirm(`¿Estás seguro de que quieres cambiar el rol a ${newRole}?`)) {
                fetch('api.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({action: 'change_role', user_id: userId, new_role: newRole})
                }).then(handleApiResponse);
            } else {
                location.reload();
            }
        });
    });

    // --- LÓGICA PARA GESTIONAR FORMULARIOS (SUPERVISOR Y SUPERUSUARIO) ---
    
    // 1. Acción de Aprobar (directa)
    document.querySelectorAll('.form-action-btn[data-action="aprobado"]').forEach(button => {
        button.addEventListener('click', function() {
            const formId = this.dataset.formId;
            if (confirm('¿Estás seguro de que quieres APROBAR este formulario?')) {
                const payload = {action: 'update_status', form_id: formId, status: 'aprobado', reason: ''};
                fetch('api.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                }).then(handleApiResponse);
            }
        });
    });

    // 2. Lógica para el Modal de Rechazo
    // Se verifica si el elemento del modal existe antes de inicializarlo.
    const rejectModalElement = document.getElementById('rejectModal');
    if (rejectModalElement) {
        const rejectModal = new bootstrap.Modal(rejectModalElement);
        const formIdInput = document.getElementById('formIdToReject');
        
        // Acción de Rechazar (prepara el modal)
        document.querySelectorAll('.reject-btn').forEach(button => {
            button.addEventListener('click', function() {
                const formId = this.dataset.formId;
                // Pone el ID del formulario en el campo oculto del modal
                if(formIdInput) {
                    formIdInput.value = formId;
                }
            });
        });

        // Acción de Confirmar Rechazo (dentro del modal)
        const confirmBtn = document.getElementById('confirmRejectBtn');
        if(confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                const formId = formIdInput.value;
                const reason = document.getElementById('rejectionReason').value.trim();

                if (reason === '') {
                    alert('Debes especificar un motivo para el rechazo.');
                    return;
                }

                const payload = {action: 'update_status', form_id: formId, status: 'rechazado', reason: reason};
                
                fetch('api.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(payload)
                }).then(handleApiResponse);
                
                // Cierra el modal después de enviar
                rejectModal.hide();
            });
        }
    }


    // Función genérica para manejar la respuesta de la API
    function handleApiResponse(response) {
        response.json().then(data => {
            if (data.success) {
                alert('Acción realizada con éxito.');
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Ocurrió un problema.'));
            }
        });
    }
});
</script>
</body>
</html>
