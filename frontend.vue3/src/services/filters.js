
import Vue from 'vue';
import moment from 'moment';

Vue.filter('datetime', function (value) {
  if (value) {
    return moment(value).format('DD/MM/YYYY HH:mm');
  }
});

Vue.filter('date', function (value) {
  if (value) {
    return moment(value).format('DD/MM/YYYY');
  }
});

Vue.filter('time', function (value) {
  if (value) {
    return moment(value).format('HH:mm');
  }
});

Vue.filter('money', function (value) {
  if (value) {
    return value.toLocaleString('pt-br', {style: "currency", currency: "BRL" });
  } else {
    return 'R$0,00'
  }
});

Vue.filter('phone', function (value) {
  if (value) {
    value = value.toString();
    if(value.length == 11) {
      return value.replace(/(\d\d)(\d\d\d\d\d)(\d\d\d\d)/, '($1)$2-$3');
    } else if (value.length == 10) {
      return value.replace(/(\d\d)(\d\d\d\d)(\d\d\d\d)/, '($1)$2-$3');
    }
  }
});

Vue.filter('cpfcnpj', function (value) {
  if (value) {
    return value.toString().replace(/\D/g, '').replace(/^(\d{2})(\d{3})?(\d{3})?(\d{4})?(\d{2})?/, "$1 $2 $3/$4-$5");
  }
});
