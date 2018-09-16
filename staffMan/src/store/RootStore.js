import { observable, action } from "mobx";
import { StackActions, NavigationActions } from "react-navigation";
import { AsyncStorage } from "react-native";

const resetAction = StackActions.reset({
	index: 0,
	actions: [NavigationActions.navigate({ routeName: "Login" })],
});

export default class RootStore {
	@action
	login(token, user = null) {
		this.auth_token = token;
		this.user  = user;	
	}

	@action
	logout(navigation) {
		this.auth_token = "";
		this.user  = null;	
		AsyncStorage.removeItem('@auth:token', (error) => navigation.dispatch(resetAction))
	}
}