function currency(Num) {
    return new Intl.NumberFormat('en-EN', { maximumSignificantDigits: 9 }).format(Num);
}
function currencyToNum(curent) {
    return curent.replace(/[,]|[.]/g, '');
}