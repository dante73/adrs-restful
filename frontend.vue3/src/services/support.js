const support = {
    ufPreposicao: function(estado) {
        //
        let lastc = estado.nome.substr(-1, 1);
        let r =
            lastc === 'a' && ! estado.sigla.match(/^(RO|RR|SC)$/) ? ' da ' :
                estado.sigla.match(/^(PE|SP|RO|RR|SC|SE)$/) ||
                (lastc === 's' && ! estado.sigla.match(/^(AM|TO)$/)) ? ' de ' : ' do ';
        //
        return r;
    }
}

export default support;