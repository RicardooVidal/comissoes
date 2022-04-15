const masks = {
    date: ['00/00/0000', false],
    time: ['00:00:00', false],
    percent: ['##0,00%', true],
    date_time: ['00/00/0000 00:00:00', false],
    phone_with_ddd: ['(00) 00000-0000', true],
    cpf: ['000.000.000-00', true],
    money: ['000.000.000.000.000,00', true],
    agencia: ['0000', false],
    digito: ['0', false],
    conta_banco: ['00000000000', false],
    rg: ['00.000.000-A', false],
}

module.exports = masks;