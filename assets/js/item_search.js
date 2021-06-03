import SearchForm from './components/SearchForm';

document.addEventListener('DOMContentLoaded', () => {
    let s = new SearchForm('/outil/search', 'item')
    s.loadData();
});
