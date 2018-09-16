// @flow
import * as React from "react";
import { observer, inject } from "mobx-react/native";

import PostView from "../../../../stories/screens/Home/components/Post/components/PostView";

@inject("rootStore")
@observer
export default class PostViewContainer extends React.Component {

	render() {
		const { params } = this.props.navigation.state;
		return <PostView  {...params } {...this.props}  />;
	}
}
