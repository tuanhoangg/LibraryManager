import './bootstrap';
import App from "./vue-components/App.vue"
import ChangePasswordModal from './vue-components/modals/ChangePasswordModal.vue'
import UserListTable from './vue-components/Pages/UserList/Table/UserListTable.vue'
import GenresListTable from './vue-components/Pages/Genres/Table/GenresListTable.vue'
import AddGenresModal from './vue-components/Pages/Genres/Modals/AddGenresModal.vue'
import AuthorListTable from './vue-components/Pages/Author/Table/AuthorListTable.vue'
import PubliserListTable from './vue-components/Pages/Publiser/Table/PubliserListTable.vue'
import BookLanguageListTable from './vue-components/Pages/BookLanguage/Table/BookLanguageListTable.vue'
import MembershipPlanListTable from './vue-components/Pages/MembershipPlan/Table/MembershipPlanListTable.vue'
import BookListTable from './vue-components/Pages/Book/Table/BookListTable.vue'
import AddBookPage from './vue-components/Pages/Book/Page/AddBookPage.vue'
import EditBookPage from './vue-components/Pages/Book/Page/EditBookPage.vue'
import SearchBookPage from './vue-components/Pages/Book/Page/SearchBookPage.vue'
import BorrowHistoryListPage from './vue-components/Pages/BorrowHistory/Page/BorrowHistoryListPage.vue'
import BorrowHistoryManagerPage from './vue-components/Pages/BorrowHistory/Page/BorrowHistoryManagerPage.vue'
import { createApp } from 'vue';

import { createBootstrap } from 'bootstrap-vue-next'
import LateReturnPage from './vue-components/Pages/LateReturn/Page/LateReturnPage.vue';
import AdminLateReturnPage from './vue-components/Pages/LateReturn/Page/AdminLateReturnPage.vue';
import FinesPage from './vue-components/Pages/Fines/Page/FinesPage.vue';
import AdminFinesPage from './vue-components/Pages/Fines/Page/AdminFinesPage.vue';
// import 'bootstrap/dist/css/bootstrap.css';
// import 'bootstrap-vue/dist/bootstrap-vue.css';
const app = createApp({
    components: {
        ChangePasswordModal,
        UserListTable,
        GenresListTable,
        AuthorListTable,
        PubliserListTable,
        BookLanguageListTable,
        BookListTable,
        AddGenresModal,
        AddBookPage,
        EditBookPage,
        MembershipPlanListTable,
        SearchBookPage,
        BorrowHistoryListPage,
        BorrowHistoryManagerPage,
        LateReturnPage,
        AdminLateReturnPage,
        FinesPage,
        AdminFinesPage,
    }
})
// configureCompat({
//     MODE: 3,
// })
// // Make BootstrapVue available throughout your project
// app.use(BootstrapVue)
// // Optionally install the BootstrapVue icon components plugin
// app.use(IconsPlugin)
app.use(createBootstrap({ components: true, directives: true })) // Change this line

app.mount('#app');