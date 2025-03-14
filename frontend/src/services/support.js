import http from './http';
import moment from "moment";

const support = {
    /**
     * Flags que serão utilizados pelo CRUD
     */
    st: {
        SELECTING: 0x0,
        CREATING: 0x1,
        READING: 0x2,
        UPDATING: 0x4,
        DELETING: 0x8
    },
    /**
     * Estes valores não são exatos, servem somente para calcular a altura das tabelas do sistema.
     */
    navbarHeight: 65,
    footerHeight: 20,
    searchbarHeight: 40,
    /**
     * Retorna qual a preposição a ser utilizada por estado (Unidade Federativa do Brasil)
     *
     * @param estado é o objeto estado retornado pelo model restful state
     * @returns {string}
     */
    ufPreposicao: function(estado) {
        //
        let lastc = estado.name.substr(-1, 1);
        let r =
            lastc === 'a' && ! estado.state_short.match(/^(RO|RR|SC)$/) ? ' da ' :
                estado.state_short.match(/^(PE|SP|RO|RR|SC|SE)$/) ||
                (lastc === 's' && ! estado.state_short.match(/^(AM|TO)$/)) ? ' de ' : ' do ';
        //
        return r;
    },
    typeTextByKey: function(types, key) {
        for (let i in types) {
            let t = types[i];
            if (t.value === key) {
                return t.text;
            }
        }
        return "";
    },
    yearsBetween: function(from, to) {
        let firstDate = moment(from);
        let secondDate = moment(to);
        let yearDiff = firstDate.diff(secondDate, "year");

        return yearDiff * -1;
    },
    getAllStates: async function() {
        let s = await http.get('state');
        return s.data.map(elm => {
            return { text: elm.name, value: elm.id, short: elm.state_short }
        });
    },
    getAllCitiesByState: async function(state) {
        let s = await http.get('city/loadAllByStateId/' + state);
        return s.data.map(elm => {
            return { text: elm.name, value: elm.id }
        });
    },
    wHeight: function() {
        return window.innerHeight;
    },
    mainHeight: function() {
        let total = window.innerHeight - 25;
        let navb = document.getElementById('navbar-container');
        let footer = document.getElementById('footer-container');

        if (navb) {
            total -= navb.offsetHeight
        }
        else {
            total -= this.navbarHeight;
        }

        if (footer) {
            total -= footer.offsetHeight;
        }
        else {
            total -= this.footerHeight;
        }

        return  total;
    },
    findIndexByKeyValue: function(obj, key, value) {
        // Navega pelo objeto em busca da chave e valor.
        for (let [k, v] of Object.entries(obj)) {
            // Encontrou a chave, verifica o valor e retorna o índice correspondente.
            if (k === key && obj[key] === value) {
                return k;
            }
            // Encontrou outro objeto, procura a chave e valor neste objeto.
            if (typeof(v) === 'object' && v !== null) {
                // Faz uma busca recursiva.
                let found = this.findIndexByKeyValue(v, key, value);
                // Se encontrou, retorna o índice.
                if (found) {
                    return k;
                }
            }
        }
        return 0;
    }
}

export default support;