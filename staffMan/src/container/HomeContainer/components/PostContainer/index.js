// @flow
import * as React from "react";
import { observer, inject } from "mobx-react/native";

import Post from "../../../../stories/screens/Home/components/Post";

@inject("rootStore")
@observer
export default class PostContainer extends React.Component {

	render() {
		return <Post {...this.props}  />;
	}
}
