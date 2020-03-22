/* eslint no-undef: "off", no-unused-vars: "off", max-len: "off", no-empty-label: "off" */

/**
 * First we will load all of this project"s JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import babelPolyfill from "babel-polyfill";
require("./bootstrap");

window.Vue = require("vue");

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import LoginForm from "./components/forms/form-components/LoginForm";
import Dataentry from "./components/request/DataEntry"
import Dataentrypart from "./components/request/DataEntryPart"
import Todo from "./components/neubus/Todo"
import BurgerMenu from "./components/BurgerMenu.vue";
import RequestComponent from "./components/request/RequestComponent.vue";
import UserAdmin from "./components/UserAdmin";
import ProjectManagement from "./components/ProjectManagement";
import Company from "./components/Company";
import IndexBuilder from "./components/admin/IndexBuilder";
import NeuDataDropdown from "./components/util/NeuDataDropdown";
import NeuForm from "./components/forms/NeuForm";
import NeuButton from "./components/util/NeuButton";
import Scan from "./components/request/Scan";
import Completed from "./components/request/Completed";
import Settings from "./components/Settings";
import UploadPage from "./components/request/todo/UploadPage";
import NewRequest from "./components/request/NewRequest";
import Review from "./components/request/Review";
import MfaForm from "./components/forms/form-components/MfaForm";
import UpdateLocation from "./components/box/UpdateLocation"
import ResetPassword from "./components/ResetPassword.vue";
import UpdateProfile from "./components/user/UpdateProfile";
import LogPage from "./components/admin/LogPage";
import smoothscroll from "smoothscroll-polyfill";
smoothscroll.polyfill();


const app = new Vue({
	el: "#app",
	components: {
		IndexBuilder,
		LoginForm,
		BurgerMenu,
		NeuDataDropdown,
		requestcomponent: RequestComponent,
		UserAdmin,
		ProjectManagement,
		NeuForm,
		Company,
		Todo,
		NeuButton,
		Scan,
		Completed,
		Dataentry,
		Settings,
		UploadPage,
		Dataentrypart,
		NewRequest,
		UpdateLocation,
		MfaForm,
        UpdateProfile,
		ResetPassword,
		Review,
		LogPage
	}
});
