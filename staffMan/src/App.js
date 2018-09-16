// @flow
import React from "react";
import { createStackNavigator, createDrawerNavigator } from "react-navigation";
import { Root } from "native-base";
import Login from "./container/LoginContainer";
import Home from "./container/HomeContainer";
import BlankPage from "./container/BlankPageContainer";
import Admin from "./container/AdminContainer";
import SuperAdmin from "./container/SuperAdminContainer";
import Settings from "./container/SettingsContainer";
import Sidebar from "./container/SidebarContainer";
import PostView from "./container/HomeContainer/components/PostContainer/PostViewContainer";
import PollView from "./container/HomeContainer/components/PollContainer/PollViewContainer";

const Drawer = createDrawerNavigator(
	{
		Home: { screen: Home },
		AdminPanel: { screen: Admin },
		SuperAdminPanel: { screen: SuperAdmin },
		Settings: { screen: Settings },
		PostView: { screen : PostView },
		PollView: { screen : PollView },
	},
	{
		initialRouteName: "Home",
		contentComponent: props => <Sidebar {...props} />,
	}
);

const App = createStackNavigator(
	{
		Login: { screen: Login },
		BlankPage: { screen: BlankPage },
		Drawer: { screen: Drawer },
	},
	{
		initialRouteName: "Login",
		headerMode: "none",
	}
);

export default () => (
	<Root>
		<App />
	</Root>
);
