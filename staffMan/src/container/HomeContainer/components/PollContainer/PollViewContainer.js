// @flow
import * as React from "react";
import { observer, inject } from "mobx-react/native";

import PollView from "../../../../stories/screens/Home/components/Poll/components/PollView";

@inject("rootStore")
@observer
export default class PollViewContainer extends React.Component {

	render() {
		const { params } = this.props.navigation.state;
		return <PollView  {...params } {...this.props}  />;
	}
}
