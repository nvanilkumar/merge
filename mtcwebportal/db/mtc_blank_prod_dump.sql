USE [mtcstprodtestdb]
GO
/****** Object:  Table [dbo].[categories]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[categories](
	[category_id] [int] IDENTITY(1,1) NOT NULL,
	[category_name] [varchar](100) NOT NULL,
	[status] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_categories] PRIMARY KEY CLUSTERED 
(
	[category_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[comments]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[comments](
	[comment_id] [int] IDENTITY(1,1) NOT NULL,
	[comment_text] [varchar](max) NOT NULL,
	[topic_id] [int] NOT NULL,
	[status] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[created_by] [int] NOT NULL,
	[updated_at] [datetime] NOT NULL,
	[updated_by] [int] NOT NULL,
 CONSTRAINT [PK_comments] PRIMARY KEY CLUSTERED 
(
	[comment_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[event_users]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[event_users](
	[event_user_id] [int] IDENTITY(1,1) NOT NULL,
	[event_id] [int] NOT NULL,
	[user_id] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
	[user_attend_status] [int] NOT NULL,
 CONSTRAINT [PK_event_users] PRIMARY KEY CLUSTERED 
(
	[event_user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[events]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[events](
	[event_name] [varchar](200) NOT NULL,
	[event_description] [varchar](1000) NOT NULL,
	[status] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
	[event_id] [int] IDENTITY(1,1) NOT NULL,
	[event_start_date] [datetime] NOT NULL,
	[event_end_date] [datetime] NOT NULL,
 CONSTRAINT [PK__events__2370F72792B4F86B] PRIMARY KEY CLUSTERED 
(
	[event_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[group_users]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[group_users](
	[group_user_id] [int] IDENTITY(1,1) NOT NULL,
	[group_id] [int] NOT NULL,
	[user_id] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_group_users] PRIMARY KEY CLUSTERED 
(
	[group_user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[groups]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[groups](
	[group_id] [int] IDENTITY(1,1) NOT NULL,
	[group_name] [varchar](200) NOT NULL,
	[status] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
	[created_by] [int] NOT NULL,
	[updated_by] [int] NOT NULL,
 CONSTRAINT [PK_groups] PRIMARY KEY CLUSTERED 
(
	[group_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[links]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[links](
	[link_id] [int] IDENTITY(1,1) NOT NULL,
	[link_name] [varchar](100) NOT NULL,
	[link_url] [varchar](200) NOT NULL,
	[menu_position] [int] NULL,
	[status] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_links] PRIMARY KEY CLUSTERED 
(
	[link_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[notification_users]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[notification_users](
	[notification_user_id] [int] IDENTITY(1,1) NOT NULL,
	[notification_id] [int] NOT NULL,
	[user_id] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_notification_users] PRIMARY KEY CLUSTERED 
(
	[notification_user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[notifications]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[notifications](
	[notification_id] [int] IDENTITY(1,1) NOT NULL,
	[message] [varchar](500) NOT NULL,
	[created_by] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
	[status] [varchar](50) NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_notifications] PRIMARY KEY CLUSTERED 
(
	[notification_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[role_users]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[role_users](
	[role_user_id] [int] IDENTITY(1,1) NOT NULL,
	[role_id] [int] NOT NULL,
	[user_id] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_role_users] PRIMARY KEY CLUSTERED 
(
	[role_user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[roles]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[roles](
	[role_id] [int] IDENTITY(1,1) NOT NULL,
	[role_name] [varchar](100) NOT NULL,
	[status] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_roles] PRIMARY KEY CLUSTERED 
(
	[role_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[statuslookup]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[statuslookup](
	[status_id] [int] IDENTITY(1,1) NOT NULL,
	[status_name] [varchar](100) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_statuslookup] PRIMARY KEY CLUSTERED 
(
	[status_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[survey_users]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[survey_users](
	[survey_user_id] [int] IDENTITY(1,1) NOT NULL,
	[survey_id] [int] NOT NULL,
	[user_id] [int] NOT NULL,
	[user_response_status] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_survey_users] PRIMARY KEY CLUSTERED 
(
	[survey_user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[surveys]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[surveys](
	[survey_id] [int] IDENTITY(1,1) NOT NULL,
	[survey_name] [varchar](200) NOT NULL,
	[survey_description] [varchar](1000) NOT NULL,
	[survey_code] [varchar](100) NOT NULL,
	[survey_start_date] [datetime] NOT NULL,
	[survey_end_date] [datetime] NOT NULL,
	[status] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_surveys] PRIMARY KEY CLUSTERED 
(
	[survey_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[topics]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[topics](
	[topic_id] [int] IDENTITY(1,1) NOT NULL,
	[topic_title] [varchar](500) NOT NULL,
	[topic_description] [varchar](1000) NOT NULL,
	[category_id] [int] NOT NULL,
	[status] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[created_by] [int] NOT NULL,
	[updated_at] [datetime] NOT NULL,
	[updated_by] [int] NOT NULL,
 CONSTRAINT [PK_topics] PRIMARY KEY CLUSTERED 
(
	[topic_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[user_tokens]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[user_tokens](
	[user_token_id] [int] IDENTITY(1,1) NOT NULL,
	[device_token_id] [varchar](300) NOT NULL,
	[user_id] [int] NOT NULL,
	[device_type] [int] NOT NULL,
	[status] [varchar](50) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_user_tokens] PRIMARY KEY CLUSTERED 
(
	[user_token_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
/****** Object:  Table [dbo].[users]    Script Date: 30-06-2017 PM 12:49:45 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
SET ANSI_PADDING ON
GO
CREATE TABLE [dbo].[users](
	[user_id] [int] IDENTITY(1,1) NOT NULL,
	[first_name] [varchar](500) NOT NULL,
	[last_name] [varchar](500) NOT NULL,
	[username] [varchar](200) NOT NULL,
	[password] [varchar](500) NOT NULL,
	[email] [varchar](200) NOT NULL,
	[mobileno] [bigint] NULL,
	[address] [varchar](1000) NULL,
	[country] [varchar](200) NULL,
	[pincode] [varchar](200) NULL,
	[profile_image_url] [varchar](500) NULL,
	[salt_string] [varchar](200) NULL,
	[status] [varchar](50) NULL,
	[created_at] [datetime] NOT NULL,
	[created_by] [int] NOT NULL,
	[updated_at] [datetime] NOT NULL,
	[updated_by] [int] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[user_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
SET ANSI_PADDING OFF
GO
SET IDENTITY_INSERT [dbo].[role_users] ON 

INSERT [dbo].[role_users] ([role_user_id], [role_id], [user_id], [created_at], [updated_at]) VALUES (1, 1, 1, CAST(0x0000A771000894D4 AS DateTime), CAST(0x0000A771000894D4 AS DateTime))
SET IDENTITY_INSERT [dbo].[role_users] OFF
SET IDENTITY_INSERT [dbo].[roles] ON 

INSERT [dbo].[roles] ([role_id], [role_name], [status], [created_at], [updated_at]) VALUES (1, N'staff', N'active', CAST(0x0000A7710003F1D7 AS DateTime), CAST(0x0000A7710003F1D7 AS DateTime))
INSERT [dbo].[roles] ([role_id], [role_name], [status], [created_at], [updated_at]) VALUES (2, N'student', N'active', CAST(0x0000A7710003FA25 AS DateTime), CAST(0x0000A7710003FA25 AS DateTime))
SET IDENTITY_INSERT [dbo].[roles] OFF
SET IDENTITY_INSERT [dbo].[statuslookup] ON 

INSERT [dbo].[statuslookup] ([status_id], [status_name], [created_at], [updated_at]) VALUES (1, N'pending', CAST(0x0000A76E00A3AFDA AS DateTime), CAST(0x0000A76E00A3AFDA AS DateTime))
INSERT [dbo].[statuslookup] ([status_id], [status_name], [created_at], [updated_at]) VALUES (2, N'accepted', CAST(0x0000A76E00A3BA9B AS DateTime), CAST(0x0000A76E00A3BA9B AS DateTime))
INSERT [dbo].[statuslookup] ([status_id], [status_name], [created_at], [updated_at]) VALUES (3, N'rejected', CAST(0x0000A76E00A3C5DB AS DateTime), CAST(0x0000A76E00A3C5DB AS DateTime))
INSERT [dbo].[statuslookup] ([status_id], [status_name], [created_at], [updated_at]) VALUES (4, N'ios', CAST(0x0000A76F00026354 AS DateTime), CAST(0x0000A76F00026354 AS DateTime))
INSERT [dbo].[statuslookup] ([status_id], [status_name], [created_at], [updated_at]) VALUES (5, N'android', CAST(0x0000A7700001A8BB AS DateTime), CAST(0x0000A7700001A8BB AS DateTime))
SET IDENTITY_INSERT [dbo].[statuslookup] OFF
SET IDENTITY_INSERT [dbo].[users] ON 

INSERT [dbo].[users] ([user_id], [first_name], [last_name], [username], [password], [email], [mobileno], [address], [country], [pincode], [profile_image_url], [salt_string], [status], [created_at], [created_by], [updated_at], [updated_by]) VALUES (1, N'Anil', N'Kumar', N'staff', N'$2y$10$PwV7vqRQ2tjEcAvJf.5b8eiyOv32kNkMfwCv12VpKDHcO18jPsLIu', N'yoursanil22@gmail.com', NULL, NULL, NULL, NULL, NULL, N'vnqWWIKFSceutriEUToW2tWcZDdxk5834YtCDEX6b93JIeogtYdmf3OXS43K1Kj9', N'active', CAST(0x0000A7620062E7CF AS DateTime), 1, CAST(0x0000A7620062E7CF AS DateTime), 1)
SET IDENTITY_INSERT [dbo].[users] OFF
ALTER TABLE [dbo].[categories] ADD  CONSTRAINT [DF_categories_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[categories] ADD  CONSTRAINT [DF_categories_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[comments] ADD  CONSTRAINT [DF_comments_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[comments] ADD  CONSTRAINT [DF_comments_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[event_users] ADD  CONSTRAINT [DF_event_users_user_attend_status2]  DEFAULT ((1)) FOR [user_attend_status]
GO
ALTER TABLE [dbo].[events] ADD  CONSTRAINT [DF_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[events] ADD  CONSTRAINT [DF_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[group_users] ADD  CONSTRAINT [DF_group_users_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[group_users] ADD  CONSTRAINT [DF_group_users_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[groups] ADD  CONSTRAINT [DF_groups_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[groups] ADD  CONSTRAINT [DF_groups_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[links] ADD  CONSTRAINT [DF_links_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[links] ADD  CONSTRAINT [DF_links_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[notification_users] ADD  CONSTRAINT [DF_notification_users_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[notification_users] ADD  CONSTRAINT [DF_notification_users_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[notifications] ADD  CONSTRAINT [DF_notifications_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[notifications] ADD  CONSTRAINT [DF_notifications_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[role_users] ADD  CONSTRAINT [DF_role_users_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[role_users] ADD  CONSTRAINT [DF_role_users_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[roles] ADD  CONSTRAINT [DF_roles_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[roles] ADD  CONSTRAINT [DF_roles_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[statuslookup] ADD  CONSTRAINT [DF_statuslookup_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[statuslookup] ADD  CONSTRAINT [DF_statuslookup_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[survey_users] ADD  CONSTRAINT [DF_survey_users_user_response_status]  DEFAULT ((1)) FOR [user_response_status]
GO
ALTER TABLE [dbo].[survey_users] ADD  CONSTRAINT [DF_survey_users_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[survey_users] ADD  CONSTRAINT [DF_survey_users_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[surveys] ADD  CONSTRAINT [DF_surveys_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[surveys] ADD  CONSTRAINT [DF_surveys_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[topics] ADD  CONSTRAINT [DF_topics_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[topics] ADD  CONSTRAINT [DF_topics_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[user_tokens] ADD  CONSTRAINT [DF_user_tokens_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[user_tokens] ADD  CONSTRAINT [DF_user_tokens_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
ALTER TABLE [dbo].[users] ADD  CONSTRAINT [DF_users_created_at]  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[users] ADD  CONSTRAINT [DF_users_updated_at]  DEFAULT (getdate()) FOR [updated_at]
GO
EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'links', @level2type=N'COLUMN',@level2name=N'created_at'
GO
