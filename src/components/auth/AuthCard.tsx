import React, { useState } from "react";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import LoginForm from "./LoginForm";
import SignupForm from "./SignupForm";
import RecoveryForm from "./RecoveryForm";
import { Card, CardContent } from "@/components/ui/card";

interface AuthCardProps {
  defaultTab?: "login" | "signup" | "recovery";
  onLoginSubmit?: (values: any) => void;
  onSignupComplete?: () => void;
  onRecoveryComplete?: (data: any) => void;
}

const AuthCard = ({
  defaultTab = "login",
  onLoginSubmit = () => {},
  onSignupComplete = () => {},
  onRecoveryComplete = () => {},
}: AuthCardProps) => {
  const [activeTab, setActiveTab] = useState<string>(defaultTab);

  const handleTabChange = (value: string) => {
    setActiveTab(value);
  };

  const handleForgotPassword = () => {
    setActiveTab("recovery");
  };

  const handleSignUpClick = () => {
    setActiveTab("signup");
  };

  return (
    <div className="w-full max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
      <Tabs
        value={activeTab}
        onValueChange={handleTabChange}
        className="w-full"
      >
        <TabsList className="grid w-full grid-cols-3">
          <TabsTrigger value="login">Login</TabsTrigger>
          <TabsTrigger value="signup">Sign Up</TabsTrigger>
          <TabsTrigger value="recovery">Recovery</TabsTrigger>
        </TabsList>

        <TabsContent value="login" className="p-0">
          <LoginForm
            onSubmit={onLoginSubmit}
            onForgotPassword={handleForgotPassword}
            onSignUp={handleSignUpClick}
          />
        </TabsContent>

        <TabsContent value="signup" className="p-0">
          <SignupForm
            onComplete={() => {
              onSignupComplete();
              setActiveTab("login");
            }}
          />
        </TabsContent>

        <TabsContent value="recovery" className="p-0">
          <RecoveryForm
            onComplete={(data) => {
              onRecoveryComplete(data);
              setActiveTab("login");
            }}
          />
        </TabsContent>
      </Tabs>
    </div>
  );
};

export default AuthCard;
