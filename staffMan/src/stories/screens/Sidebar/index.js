import * as React from "react";
import { Text, Container, List, ListItem, Content } from "native-base";
import { observer, inject } from "mobx-react/native";

@inject("rootStore")
@observer
export default class Sidebar extends React.Component {
	logout() {
		this.props.rootStore.logout(this.props.navigation);
	}

	getRoutes() {
		return [
			{
				route: "Home",
				caption: "Home",
			},
			{
				route: "BlankPage",
				caption: "Blank Page",
			},
			{
				route: "Settings",
				caption: "Settings",
			},
			{
				route: "Blank Page",
				caption: "Notifications",
			},

			this.props.rootStore.user && this.props.rootStore.user.is_admin ? {
				route: "AdminPanel",
				caption: "Admin Panel",
			} : {},

			this.props.rootStore.user && this.props.rootStore.user.is_super_admin ? {
				route: "SuperAdminPanel",
				caption: "Super Admin Panel",
			} : {},

			{
				route: "Logout",
				caption: "Logout",
			},
		].filter(item => item.caption);
	}

	render() {
		const routes = this.getRoutes();
		return (
			<Container>
				<Content>
					<List
						style={{ marginTop: 40 }}
						dataArray={routes}
						renderRow={data => {
							return (
								<ListItem
									button
									onPress={() => {
										data.route === "Logout"
											? this.logout()
											: this.props.navigation.navigate(data.route);
									}}
								>
									<Text>{data.caption}</Text>
								</ListItem>
							);
						}}
					/>
				</Content>
			</Container>
		);
	}
}
