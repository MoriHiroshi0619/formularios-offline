function formatarCpf(texto){
    texto = texto.replace(/\D/g, '');
    if (texto.length > 11) texto = texto.slice(0, 11);
    let formato = texto.replace(/(\d{3})(\d)/, '$1.$2');
    formato = formato.replace(/(\d{3})(\d)/, '$1.$2');
    formato = formato.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    return formato;
}
document.addEventListener('DOMContentLoaded', () => {
    const inputsCpf = document.querySelectorAll('input.cpf');

    inputsCpf.forEach(input => {
        input.addEventListener('input', (event) => {
            const valorOriginal = event.target.value;
            event.target.value = formatarCpf(valorOriginal);
        });
    });
});


