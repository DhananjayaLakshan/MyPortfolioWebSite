(() => {
    window.addEventListener("elementor/init", () => {
        // for adding items to widget context menu use this below line
        elementor.hooks.addFilter(
            "elements/widget/contextMenuGroups",
            (groups, view) => {

                // Insert Parallax Animation group as forth group
                groups.splice(3, 0, {
                    name: "parallaxAnimationGroup",
                    actions: [
                        {
                            name: "copyParallaxAnimation",
                            title: "Copy Parallax Animation",
                            callback: () => {
                                const exportedSettings = {};

                                [
                                    "aux_parallax_anims_enable",
                                    "aux_parallax_in_anims",
                                    "aux_parallax_out_anims",
                                    "aux_parallax_horizontal_transform",
                                    "aux_parallax_vertical_transform",
                                    "aux_parallax_rotate_transform",
                                    "aux_parallax_scale_transform",
                                    "aux_parallax_animation_easing",
                                    "aux_parallax_animation_duration",
                                    "aux_parallax_animation_delay",
                                    "aux_parallax_viewport_top_origin",
                                    "aux_parallax_viewport_bottom_origin",
                                    "aux_parallax_element_origin",
                                    "aux_parallax_animation_disable_on",
                                    "aux_parallax_animation_disable_under",
                                ].forEach((id) => {
                                    exportedSettings[id] =
                                        view.model.getSetting(id);
                                });

                                localStorage.setItem(
                                    "auxElementorParallaxAnimationSettings",
                                    JSON.stringify(exportedSettings)
                                );
                            },
                        },
                        {
                            name: "pasteParallaxAnimation",
                            title: "Paste Parallax Animation",
                            isEnabled: () =>
                                !!localStorage.getItem(
                                    "auxElementorParallaxAnimationSettings"
                                ),
                            callback: () => {
                                const settings = JSON.parse(
                                    localStorage.getItem(
                                        "auxElementorParallaxAnimationSettings"
                                    )
                                );

                                Object.keys(settings).forEach((setting) => {
                                    view.model.setSetting(
                                        setting,
                                        settings[setting]
                                    );
                                });

                                view.model.renderRemoteServer();
                            },
                        },
                    ],
                });

                return groups;
            }
        );

        // for adding items to section context menu use this below line
        elementor.hooks.addFilter(
            "elements/section/contextMenuGroups",
            (groups, view) => {
                return groups;
            }
        );

        // for adding items to column context menu use this below line
        elementor.hooks.addFilter(
            "elements/column/contextMenuGroups",
            (groups, view) => {
                return groups;
            }
        );
    });
})();
