import Dashboard from "./components/Dashboard.vue";
import ListAppointments from "./components/pages/appointments/ListAppointments.vue";
import UserList from "./components/pages/users/UserList.vue";
import UpdateSetting from "./components/pages/settings/UpdateSetting.vue";
import UpdateProfile from "./components/pages/profile/UpdateProfile.vue";

export default [
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard,
    },
    {
        path: '/admin/appointments',
        name: 'admin.appointments',
        component: ListAppointments,
    },
    {
        path: '/admin/users',
        name: 'admin.users',
        component: UserList,
    },
    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: UpdateSetting,
    },
    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: UpdateProfile,
    }
]
