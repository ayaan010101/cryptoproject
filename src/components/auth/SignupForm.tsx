import React, { useState } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
} from "@/components/ui/card";
import {
  Form,
  FormControl,
  FormDescription,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Progress } from "@/components/ui/progress";
import {
  Check,
  ChevronRight,
  User,
  Mail,
  Lock,
  Key,
  AlertCircle,
} from "lucide-react";

const personalDetailsSchema = z.object({
  firstName: z
    .string()
    .min(2, { message: "First name must be at least 2 characters" }),
  lastName: z
    .string()
    .min(2, { message: "Last name must be at least 2 characters" }),
  email: z.string().email({ message: "Please enter a valid email address" }),
  phone: z.string().min(10, { message: "Please enter a valid phone number" }),
});

const credentialsSchema = z
  .object({
    username: z
      .string()
      .min(4, { message: "Username must be at least 4 characters" }),
    password: z
      .string()
      .min(8, { message: "Password must be at least 8 characters" }),
    confirmPassword: z.string(),
  })
  .refine((data) => data.password === data.confirmPassword, {
    message: "Passwords don't match",
    path: ["confirmPassword"],
  });

const securityKeySchema = z.object({
  acceptTerms: z.boolean().refine((val) => val === true, {
    message: "You must accept the terms and conditions",
  }),
});

type PersonalDetailsFormValues = z.infer<typeof personalDetailsSchema>;
type CredentialsFormValues = z.infer<typeof credentialsSchema>;
type SecurityKeyFormValues = z.infer<typeof securityKeySchema>;

interface SignupFormProps {
  onComplete?: () => void;
}

const SignupForm = ({ onComplete = () => {} }: SignupFormProps) => {
  const [step, setStep] = useState<number>(1);
  const [personalDetails, setPersonalDetails] =
    useState<PersonalDetailsFormValues>({
      firstName: "",
      lastName: "",
      email: "",
      phone: "",
    });
  const [credentials, setCredentials] = useState<CredentialsFormValues>({
    username: "",
    password: "",
    confirmPassword: "",
  });
  const [securityKey, setSecurityKey] = useState<string>("");

  const personalDetailsForm = useForm<PersonalDetailsFormValues>({
    resolver: zodResolver(personalDetailsSchema),
    defaultValues: personalDetails,
  });

  const credentialsForm = useForm<CredentialsFormValues>({
    resolver: zodResolver(credentialsSchema),
    defaultValues: credentials,
  });

  const securityKeyForm = useForm<SecurityKeyFormValues>({
    resolver: zodResolver(securityKeySchema),
    defaultValues: {
      acceptTerms: false,
    },
  });

  const handlePersonalDetailsSubmit = (data: PersonalDetailsFormValues) => {
    setPersonalDetails(data);
    setStep(2);
  };

  const handleCredentialsSubmit = (data: CredentialsFormValues) => {
    setCredentials(data);
    // Generate a random security key
    const generatedKey =
      Math.random().toString(36).substring(2, 15) +
      Math.random().toString(36).substring(2, 15);
    setSecurityKey(generatedKey);
    setStep(3);
  };

  const handleSecurityKeySubmit = (data: SecurityKeyFormValues) => {
    // In a real app, this would send the data to the server
    console.log("Signup complete", {
      ...personalDetails,
      ...credentials,
      securityKey,
    });
    onComplete();

    // Redirect to dashboard (for demo purposes)
    setTimeout(() => {
      window.location.href = "/dashboard";
    }, 1000);
  };

  const renderStepIndicator = () => {
    return (
      <div className="mb-6">
        <div className="flex justify-between mb-2">
          <span className="text-sm font-medium">Step {step} of 3</span>
          <span className="text-sm font-medium">{getStepTitle(step)}</span>
        </div>
        <Progress value={(step / 3) * 100} className="h-2" />
        <div className="flex justify-between mt-2">
          <div
            className={`flex items-center ${step >= 1 ? "text-primary" : "text-muted-foreground"}`}
          >
            <div
              className={`w-8 h-8 rounded-full flex items-center justify-center ${step >= 1 ? "bg-primary text-primary-foreground" : "bg-muted text-muted-foreground"}`}
            >
              {step > 1 ? <Check size={16} /> : <User size={16} />}
            </div>
            <span className="ml-2 text-xs">Personal Details</span>
          </div>
          <div
            className={`flex items-center ${step >= 2 ? "text-primary" : "text-muted-foreground"}`}
          >
            <div
              className={`w-8 h-8 rounded-full flex items-center justify-center ${step >= 2 ? "bg-primary text-primary-foreground" : "bg-muted text-muted-foreground"}`}
            >
              {step > 2 ? <Check size={16} /> : <Lock size={16} />}
            </div>
            <span className="ml-2 text-xs">Credentials</span>
          </div>
          <div
            className={`flex items-center ${step >= 3 ? "text-primary" : "text-muted-foreground"}`}
          >
            <div
              className={`w-8 h-8 rounded-full flex items-center justify-center ${step >= 3 ? "bg-primary text-primary-foreground" : "bg-muted text-muted-foreground"}`}
            >
              <Key size={16} />
            </div>
            <span className="ml-2 text-xs">Security Key</span>
          </div>
        </div>
      </div>
    );
  };

  const getStepTitle = (stepNumber: number) => {
    switch (stepNumber) {
      case 1:
        return "Personal Details";
      case 2:
        return "Create Credentials";
      case 3:
        return "Security Key";
      default:
        return "";
    }
  };

  return (
    <Card className="w-full max-w-md mx-auto bg-white">
      <CardHeader>
        <CardTitle className="text-xl font-bold text-center">
          Create Account
        </CardTitle>
      </CardHeader>
      <CardContent>
        {renderStepIndicator()}

        {step === 1 && (
          <Form {...personalDetailsForm}>
            <form
              onSubmit={personalDetailsForm.handleSubmit(
                handlePersonalDetailsSubmit,
              )}
              className="space-y-4"
            >
              <FormField
                control={personalDetailsForm.control}
                name="firstName"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>First Name</FormLabel>
                    <FormControl>
                      <Input placeholder="John" {...field} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={personalDetailsForm.control}
                name="lastName"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Last Name</FormLabel>
                    <FormControl>
                      <Input placeholder="Doe" {...field} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={personalDetailsForm.control}
                name="email"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Email</FormLabel>
                    <FormControl>
                      <Input
                        type="email"
                        placeholder="john.doe@example.com"
                        {...field}
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={personalDetailsForm.control}
                name="phone"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Phone Number</FormLabel>
                    <FormControl>
                      <Input placeholder="+1 (555) 123-4567" {...field} />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <Button type="submit" className="w-full">
                Continue <ChevronRight className="ml-2" size={16} />
              </Button>
            </form>
          </Form>
        )}

        {step === 2 && (
          <Form {...credentialsForm}>
            <form
              onSubmit={credentialsForm.handleSubmit(handleCredentialsSubmit)}
              className="space-y-4"
            >
              <FormField
                control={credentialsForm.control}
                name="username"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Username</FormLabel>
                    <FormControl>
                      <Input placeholder="johndoe" {...field} />
                    </FormControl>
                    <FormDescription>
                      This will be your login identifier
                    </FormDescription>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={credentialsForm.control}
                name="password"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Password</FormLabel>
                    <FormControl>
                      <Input
                        type="password"
                        placeholder="••••••••"
                        {...field}
                      />
                    </FormControl>
                    <FormDescription>
                      Must be at least 8 characters
                    </FormDescription>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={credentialsForm.control}
                name="confirmPassword"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Confirm Password</FormLabel>
                    <FormControl>
                      <Input
                        type="password"
                        placeholder="••••••••"
                        {...field}
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <div className="flex justify-between pt-2">
                <Button
                  variant="outline"
                  type="button"
                  onClick={() => setStep(1)}
                >
                  Back
                </Button>
                <Button type="submit">
                  Continue <ChevronRight className="ml-2" size={16} />
                </Button>
              </div>
            </form>
          </Form>
        )}

        {step === 3 && (
          <Form {...securityKeyForm}>
            <form
              onSubmit={securityKeyForm.handleSubmit(handleSecurityKeySubmit)}
              className="space-y-4"
            >
              <div className="p-4 border rounded-md bg-muted">
                <h3 className="font-medium mb-2 flex items-center">
                  <Key className="mr-2" size={18} /> Your Security Key
                </h3>
                <p className="text-sm mb-4">
                  Please save this key in a secure location. You will need it
                  for account recovery.
                </p>
                <div className="p-3 bg-primary/10 rounded border border-primary/20 font-mono text-sm break-all">
                  {securityKey}
                </div>
              </div>

              <div className="p-4 border rounded-md bg-yellow-50 text-yellow-800 flex items-start">
                <AlertCircle className="mr-2 mt-0.5 flex-shrink-0" size={18} />
                <div className="text-sm">
                  <p className="font-medium">Important:</p>
                  <p>
                    If you lose this security key, you will need to pay a fee in
                    USDT to recover your account.
                  </p>
                </div>
              </div>

              <FormField
                control={securityKeyForm.control}
                name="acceptTerms"
                render={({ field }) => (
                  <FormItem className="flex flex-row items-start space-x-3 space-y-0 rounded-md border p-4">
                    <FormControl>
                      <input
                        type="checkbox"
                        className="h-4 w-4 mt-1"
                        checked={field.value}
                        onChange={field.onChange}
                      />
                    </FormControl>
                    <div className="space-y-1 leading-none">
                      <FormLabel>
                        I have saved my security key and accept the terms and
                        conditions
                      </FormLabel>
                      <FormMessage />
                    </div>
                  </FormItem>
                )}
              />

              <div className="flex justify-between pt-2">
                <Button
                  variant="outline"
                  type="button"
                  onClick={() => setStep(2)}
                >
                  Back
                </Button>
                <Button type="submit">Complete Registration</Button>
              </div>
            </form>
          </Form>
        )}
      </CardContent>
      <CardFooter className="flex justify-center border-t pt-6">
        <p className="text-sm text-muted-foreground">
          Already have an account?{" "}
          <a href="#" className="text-primary font-medium hover:underline">
            Sign in
          </a>
        </p>
      </CardFooter>
    </Card>
  );
};

export default SignupForm;
