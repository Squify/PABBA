import SearchForm from './components/SearchForm';

document.addEventListener('DOMContentLoaded', () => {
    let s = new SearchForm('/tutorial/search', 'tutorial')
    s.loadData();
});
