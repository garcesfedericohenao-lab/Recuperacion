/**
 * Scripts del Sistema de Seguimiento de Reparaciones Tecnológicas
 */

document.addEventListener('DOMContentLoaded', function () {
    // -------------------------------------------------------------
    // 1. Validación de Diagnóstico al Cerrar Reparación
    // Regla obligatoria: No se puede cerrar un servicio sin diagnóstico.
    // -------------------------------------------------------------
    const formEditar = document.getElementById('formEditarServicio');
    const selectEstado = document.getElementById('estado');
    const textareaDiagnostico = document.getElementById('diagnostico');
    const diagnosticoHelp = document.getElementById('diagnosticoHelp');

    function checkCierreValidation() {
        if (!selectEstado || !textareaDiagnostico) return;

        const isCerrado = selectEstado.value === 'Cerrado';
        const isVacio = textareaDiagnostico.value.trim() === '';

        if (isCerrado) {
            textareaDiagnostico.setAttribute('required', 'required');
            if (diagnosticoHelp) {
                diagnosticoHelp.innerHTML = '<span class="text-danger fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> ¡Obligatorio! Para cerrar la orden debe ingresar el diagnóstico y solución aplicada.</span>';
            }
            if (isVacio) {
                textareaDiagnostico.classList.add('is-invalid');
            } else {
                textareaDiagnostico.classList.remove('is-invalid');
                textareaDiagnostico.classList.add('is-valid');
            }
        } else {
            textareaDiagnostico.removeAttribute('required');
            textareaDiagnostico.classList.remove('is-invalid');
            textareaDiagnostico.classList.remove('is-valid');
            if (diagnosticoHelp) {
                diagnosticoHelp.innerHTML = '<span class="text-muted">Describa las pruebas realizadas, fallas detectadas y componentes reparados o cambiados.</span>';
            }
        }
    }

    if (selectEstado && textareaDiagnostico) {
        selectEstado.addEventListener('change', checkCierreValidation);
        textareaDiagnostico.addEventListener('input', checkCierreValidation);
        // Ejecución inicial al cargar la página
        checkCierreValidation();
    }

    if (formEditar) {
        formEditar.addEventListener('submit', function (e) {
            if (selectEstado && textareaDiagnostico) {
                if (selectEstado.value === 'Cerrado' && textareaDiagnostico.value.trim() === '') {
                    e.preventDefault();
                    e.stopPropagation();
                    textareaDiagnostico.classList.add('is-invalid');
                    textareaDiagnostico.focus();
                    
                    // Mostrar alerta flotante o alert nativo accesible
                    alert('VALIDACIÓN REQUERIDA:\nNo es posible marcar el servicio como "Cerrado" con el campo de diagnóstico vacío.\nPor favor ingrese los detalles del diagnóstico técnico y la solución aplicada.');
                    return false;
                }
            }
        });
    }

    // -------------------------------------------------------------
    // 2. Confirmación de Eliminación Segura
    // -------------------------------------------------------------
    const deleteButtons = document.querySelectorAll('.btn-eliminar-servicio');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function (e) {
            const numero = this.getAttribute('data-numero') || 'este registro';
            const cliente = this.getAttribute('data-cliente') || '';
            const msg = `¿Está totalmente seguro de eliminar este registro?\n\nNúmero: ${numero}\nCliente: ${cliente}\n\nEsta operación eliminará permanentemente los datos.`;
            
            if (!confirm(msg)) {
                e.preventDefault();
                return false;
            }
        });
    });

    // -------------------------------------------------------------
    // 3. Confirmación de Eliminación de Administradores
    // -------------------------------------------------------------
    const deleteAdminBtns = document.querySelectorAll('.btn-eliminar-admin');
    deleteAdminBtns.forEach(button => {
        button.addEventListener('click', function (e) {
            const nombre = this.getAttribute('data-nombre') || 'este usuario';
            const usuario = this.getAttribute('data-usuario') || '';
            const msg = `¿Está seguro de eliminar al siguiente usuario?\n\nNombre: ${nombre}\nUsuario: @${usuario}\n\nEsta acción eliminará permanentemente la cuenta del sistema.`;
            
            if (!confirm(msg)) {
                e.preventDefault();
                return false;
            }
        });
    });

    // -------------------------------------------------------------
    // 4. Copiar al Portapapeles el Número de Servicio
    // -------------------------------------------------------------
    const copyBtns = document.querySelectorAll('.btn-copy-code');
    copyBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const code = this.getAttribute('data-code');
            if (code && navigator.clipboard) {
                navigator.clipboard.writeText(code).then(() => {
                    const originalHtml = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check text-success"></i> ¡Copiado!';
                    setTimeout(() => {
                        this.innerHTML = originalHtml;
                    }, 2000);
                });
            }
        });
    });

    // -------------------------------------------------------------
    // 5. Navegación Interactiva por Pestañas (Zona de Recibimiento y Búsqueda)
    // -------------------------------------------------------------
    const tabBuscarBtn = document.getElementById('tab-buscar-btn');
    const tabInicioBtn = document.getElementById('tab-inicio-btn');
    const searchInput = document.querySelector('.swiss-search-input');

    // Botones para entrar a la pestaña de búsqueda
    const gotoSearchBtns = document.querySelectorAll('.js-goto-search');
    gotoSearchBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (tabBuscarBtn && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                const tabInstance = bootstrap.Tab.getOrCreateInstance(tabBuscarBtn);
                tabInstance.show();

                if (history.pushState) {
                    history.pushState(null, null, '#tab-buscar');
                } else {
                    location.hash = '#tab-buscar';
                }

                setTimeout(() => {
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, 150);
            }
        });
    });

    // Botones para volver a la pestaña de inicio / recibimiento
    const gotoInicioBtns = document.querySelectorAll('.js-goto-inicio');
    gotoInicioBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            if (tabInicioBtn && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
                const tabInstance = bootstrap.Tab.getOrCreateInstance(tabInicioBtn);
                tabInstance.show();

                if (history.pushState) {
                    history.pushState(null, null, '#tab-inicio');
                } else {
                    location.hash = '#tab-inicio';
                }

                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    });

    // Detectar hash en la URL al cargar
    if (window.location.hash === '#buscar' || window.location.hash === '#tab-buscar') {
        if (tabBuscarBtn && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
            const tabInstance = bootstrap.Tab.getOrCreateInstance(tabBuscarBtn);
            tabInstance.show();
            setTimeout(() => {
                if (searchInput) {
                    searchInput.focus();
                }
            }, 150);
        }
    } else if (window.location.hash === '#inicio' || window.location.hash === '#tab-inicio') {
        if (tabInicioBtn && typeof bootstrap !== 'undefined' && bootstrap.Tab) {
            const tabInstance = bootstrap.Tab.getOrCreateInstance(tabInicioBtn);
            tabInstance.show();
        }
    }
});
