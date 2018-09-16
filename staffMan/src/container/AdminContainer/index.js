// @flow
import * as React from "react";
import { observer, inject } from "mobx-react/native";

import Admin from "../../stories/screens/Admin";

export interface Props {
	navigation: any,
	mainStore: any,
}
export interface State {}

@inject("mainStore")
@observer
export default class AdminContainer extends React.Component<Props, State> {
	
	render() {
		return <Admin navigation={this.props.navigation} />;
	}
}
