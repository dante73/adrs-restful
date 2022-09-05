const settings = {
    restapi: 'http://192.168.73.252/siserp/restapi/',
    backend: 'http://localhost:5002/',
    locale: 'pt-br',
    regexp: {
        limpa_valor: new RegExp(/[-./]/g)
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
    tipos: {
        acesso: [
            { text: 'Ativo', value: 'A' },
            { text: 'Inativo', value: 'I' }
        ],
        contatos: [
            { text: 'Telefone Fixo', value: 'fixo' },
            { text: 'Telefone Celular', value: 'celular' },
            { text: 'Email', value: 'email' }
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