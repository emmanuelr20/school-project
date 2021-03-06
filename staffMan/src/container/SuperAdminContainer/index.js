// @flow
import * as React from "react";
import { observer, inject } from "mobx-react/native";

import SuperAdmin from "../../stories/screens/SuperAdmin";

export interface Props {
	navigation: any,
	mainStore: any,
}
export interface State {}

@inject("mainStore")
@observer
export default class SuperAdminContainer extends React.Component<Props, State> {
	
	render() {
		return <SuperAdmin navigation={this.props.navigation} />;
	}
}
