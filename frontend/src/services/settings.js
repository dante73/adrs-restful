const settings = {
    //restapi: 'http://192.168.73.252/siserp/restapi/',
    restapi: 'http://localhost:8000/',
    backend: 'http://localhost:8000/',
    wikipedia: 'https://pt.wikipedia.org/wiki/',
    magicKey: 'Chave secreta 1',
    locale: 'pt-br',
    colors: {
        bgTitleBar: 'bg-info',
        bgModalTitleBar: 'success',
        fgModalTitleBar: 'light'
    },
    regexp: {
        limpa_valor: new RegExp(/[-./]/g),
        limpa_telefone: new RegExp(/[() -]/g),
        valida_telefone: new RegExp(/^\d{10}$/),
        valida_celular: new RegExp(/^\d{11}$/),
        valida_email: new RegExp(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)
    },
    format: {
        date: 'DD/MM/YYYY',
        date2db: 'YYYY-MM-DD',
    },
    masks: {
        idc: 'AAA#####',
        idl: '###',
        rg: '##.###.###?-?#',
        cpf: '###.###.###-##',
        cnpj: '##.###.###/####-##',
        cep: '#####-###',
        data: '##/##/####',
        celular: '(##) # ####-####',
        telefone: '(##) ####-####'
    },
    config: {
        perPage: 13,
        perPageCompact: 16,
        maxImageSize: 1000000,
        toastErrorTimeout: 5000,
        toastSuccessTimeout: 3000,
        windowRefreshTimeout: 20    // in minutes
    },
    tipos: {
        acesso: [
            { text: 'Ativo', value: 'A' },
            { text: 'Inativo', value: 'I' }
        ],
        contatos: [
            { text: 'Telefone Fixo', value: 'F' },
            { text: 'Telefone Celular', value: 'C' },
            { text: 'Email', value: 'E' }
        ],
        documentos: [
            { text: 'Cadastro de Pessoas Físicas', value: 'CPF' },
            { text: 'Cadastro Nacional de Pessoas Jurídicas', value: 'CNPJ' },
            { text: 'Registro Geral', value: 'RG' },
        ],
        pessoa: [
            { text: 'Física', value: 'F' },
            { text: 'Jurídica', value: 'J' }
        ],
        sexo: [
            { text: 'Feminino', value: 'F' },
            { text: 'Masculino', value: 'M' },
        ],
        situacao: [
            { text: 'Ativo', value: 'A' },
            { text: 'Inativo', value: 'I' }
        ],
        tipo_cliente: [
            { text: 'Cliente', value: 'C' },
            { text: 'Revenda', value: 'R' }
        ],
        tipo_contribuinte: [
            { text: 'Contribuinte', value: 'C' },
            { text: 'Não contribuinte', value: 'N' },
            { text: 'Isento', value: 'I' }
        ]
    }
}

export default settings;