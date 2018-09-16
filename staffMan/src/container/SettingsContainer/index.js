// @flow
import * as React from "react";
import { observer, inject } from "mobx-react/native";

import Settings from "../../stories/screens/Settings";

export interface Props {
	navigation: any,
	mainStore: any,
}
export interface State {}

@inject("mainStore")
@observer
export default class SettingsContainer extends React.Component<Props, State> {
	
	render() {
		return <Settings navigation={this.props.navigation} />;
	}
}
