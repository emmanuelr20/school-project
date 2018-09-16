import MainStore from "../store/DomainStore/HomeStore";
import LoginStore from "../store/ViewStore/LoginViewStore";
import RootStore from "../store/RootStore";

export default function() {
	const mainStore = new MainStore();
	const loginForm = new LoginStore();
	const rootStore = new RootStore();

	return {
		loginForm,
		mainStore,
		rootStore
	};
}
